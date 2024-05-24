<?php

namespace App\Http\Controllers;


use App\Exports\MultiSheetExport;
use App\Models\CheckHasHazmat;
use App\Models\Checks;
use App\Models\User;
use App\Models\Hazmat;
use App\Models\LabResult;
use App\Models\Projects;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Imagick;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfReader;
use Mpdf\Mpdf;

class ReportContoller extends Controller
{
    public function exportDataInExcel(Request $request, $id, $isSample = null)
    {
        if ($request->type == 'xlsx') {
            $fileExt = 'xlsx';
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        } elseif ($request->type == 'csv') {
            $fileExt = 'csv';
            $exportFormat = \Maatwebsite\Excel\Excel::CSV;
        } elseif ($request->type == 'xls') {
            $fileExt = 'xls';
            $exportFormat = \Maatwebsite\Excel\Excel::XLS;
        } else {
            $fileExt = 'xlsx';
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        }

        $project = Projects::with('client:id,manager_name,manager_email,manager_phone,manager_address,owner_name,owner_email,owner_phone,owner_address')->findOrFail($id);

        $hazmats = Hazmat::withCount(['checkHasHazmats as check_type_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->withCount(['checkHasHazmatsSample as sample_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->withCount(['checkHasHazmatsVisual as visual_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->get();

        $checks = Checks::with('deck:id,name')->with('check_hazmats.hazmat')->where('project_id', $id);

        if ($isSample) {
            $checks = $checks->where('type', 'sample');
        }

        $checks = $checks->get();

        $filename = "projects-{$id}-" . time() . "." . $fileExt;
        return Excel::download(new MultiSheetExport($project, $hazmats, $checks), $filename, $exportFormat);
    }


    public function genratePdf($project_id)
    {
  
       
        $projectDetail = Projects::find($project_id);
        if (!$projectDetail) {
            die('Project details not found');
        }
        
        $imageData = file_get_contents($projectDetail['image']);
        $logo = 'https://sosindi.com/IHM/public/assets/images/logo.png';
        $hazmets = Hazmat::withCount(['checkHasHazmats as check_type_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->withCount(['checkHasHazmatsSample as sample_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->withCount(['checkHasHazmatsVisual as visual_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->get();
        $lebResult = LabResult::with(['check', 'hazmat'])->where('project_id', $project_id)->where('type', 'Contained')->orwhere('type', 'PCHM')->get();

        $filteredResults1 = $lebResult->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-1';
        });

        $filteredResults2 = $lebResult->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-2';
        });
        $filteredResults3 = $lebResult->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-3';
        });
        try {
            // Create an instance of mPDF with specified margins
            $mpdf = new Mpdf([
                'mode' => 'c',
                'margin_left' => 32,
                'margin_right' => 25,
                'margin_top' => 27,
                'margin_bottom' => 25,
                'margin_header' => 16,
                'margin_footer' => 13,
            ]);
        
            $mpdf->mirrorMargins = 1;
            $mpdf->defaultPageNumStyle = '1';
            $mpdf->SetDisplayMode('fullpage');
         
            // Define header content with logo
            $header = '
            <table width="100%" style="border-bottom: 1px solid #000000; vertical-align: middle; font-family: serif; font-size: 9pt; color: #000088;">
                <tr>
                    <td width="10%"><img src="' . $logo . '" width="50" /></td>
                    <td width="80%" align="center">Project Report</td>
                    <td width="10%" style="text-align: right;">{DATE j-m-Y}</td>
                </tr>
            </table>';
        
            // Define footer content with page number
            $footer = '
            <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000;">
                <tr>
                    <td width="33%">{DATE j-m-Y}</td>
                    <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right;">' . $projectDetail['ihm_table'] . '</td>
                </tr>
            </table>';
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);

          
        
            // Load main HTML content
         
            $mpdf->h2toc = ['H2' => 0, 'H3' => 1];
            $mpdf->h2bookmarks = ['H2' => 0, 'H3' => 1];
            // Set header and footer
        
            // Add Table of Contents
           
            $stylesheet = file_get_contents('public/assets/mpdf.css');
            $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
            $mpdf->WriteHTML(view('report.cover',compact('projectDetail')));
            $mpdf->TOCpagebreak();
       
            $mpdf->TOCpagebreakByArray([
                'links' => true,
                'toc-preHTML' => 'Table of Contents',
                'level'=>0,
            ]);

          $mpdf->WriteHTML(view('report.introduction',compact('hazmets','projectDetail')));
          $mpdf->AddPage('L'); // Set landscape mode for the inventory page
          $totalPages = $mpdf->page;

         
          $mpdf->WriteHTML(view('report.inventory',compact('filteredResults1', 'filteredResults2', 'filteredResults3')));
          $mpdf->AddPage('p'); // Set landscape mode for the inventory page
          $mpdf->WriteHTML(view('report.development',compact('filteredResults1', 'filteredResults2', 'filteredResults3')));
          
            // Output the PDF
            $mpdf->Output('project_report.pdf', 'I');
        } catch (\Mpdf\MpdfException $e) {
            // Handle mPDF exception
            echo $e->getMessage();
        }

    }

    protected function savePdf($content, $filename)
    {
        // Specify the folder where PDF files will be stored
        $pdfFolderPath = storage_path('app/pdf');

        // Ensure the folder exists, if not create it
        if (!is_dir($pdfFolderPath)) {
            mkdir($pdfFolderPath, 0777, true);
        }

        // Save the PDF file to the folder
        $filePath = $pdfFolderPath . '/' . $filename;
        file_put_contents($filePath, $content);
    }
}

<?php

namespace App\Http\Controllers;


use App\Exports\MultiSheetExport;
use App\Models\Checks;
use App\Models\Hazmat;
use App\Models\LabResult;
use App\Models\Projects;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use App\Models\Attechments;
use App\Models\Deck;
use App\Models\ReportMaterial;
use Dompdf\Dompdf;
use Dompdf\Options;

ini_set("pcre.backtrack_limit", "5000000");

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

        //  $deck =  Deck::with('checks.hazmats')->find($id); 

        $hazmats = Hazmat::withCount(['checkHasHazmats as check_type_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->withCount(['checkHasHazmatsSample as sample_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->withCount(['checkHasHazmatsVisual as visual_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->get();

        $checks = Checks::with('deck:id,name')->with('check_hazmats.hazmat')->where('project_id', $id);
        $ship_name = $project["ship_name"];
        $imo_number  = $project["imo_number"];

        if ($isSample) {
            $checks = $checks->where('type', 'sample');
            $filename = "Lab-Test-List-{$ship_name}" . "." . $fileExt;
        } else {
            $filename = "VSCP-{$ship_name}-{$imo_number}" . "." . $fileExt;
        }

        $checks = $checks->get();

        return Excel::download(new MultiSheetExport($project, $hazmats, $checks, $isSample), $filename, $exportFormat);
    }


    public function genratePdf($project_id)
    {


        $projectDetail = Projects::find($project_id);
        if (!$projectDetail) {
            die('Project details not found');
        }

        $imageData = file_get_contents($projectDetail['image']);
        $report_materials = ReportMaterial::where('project_id', $project_id)->get()->toArray();
        $foundItems = [];

        foreach ($report_materials as $value) {
            $index = array_search($value['structure'], array_column($report_materials, 'structure'));
            if ($index !== false) {
                $foundItems[$value['structure']] = $report_materials[$index];
            }
        }
        $options = new Options();
        $dompdf = new Dompdf($options);
        $html = '';
        $decks = Deck::with(['checks' => function ($query) {
            $query->whereHas('check_hazmats', function ($query) {
                $query->where('type', 'PCHM')->orWhere('type', 'Contained');
            });
        }])->where('project_id', $project_id)->get();
        $html = '';
        $html .= "<h3>Location Diagram</h3>";
        foreach ($decks as $deck) {
            // Convert the image to base64
            $imagePath = $deck['image'];
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;

            // Main container
            $html .= '<div style="position: relative;">';
            if (count($deck['checks']) > 0) {
                // Background image using base64
                $html .= '<img src="' . $imageBase64 . '" />';

                // Container for checks
                $html .= '<div id="showDeckCheck" style="">';

                if (!empty($deck['checks'])) {
                    foreach ($deck['checks'] as $key => $value) {
                        $top = $value->position_top - ($value->isApp == 1 ? 20 : 0);
                        $left = $value->position_left - ($value->isApp == 1 ? 20 : 0);

                        // Position the dot using fixed units
                        $html .= '<div class="dot" style="position: absolute; top: ' . $top . 'px; left: ' . $left . 'px; width: 20px; height: 20px; border: 2px solid red; background: red;border-radious:50;  text-align: center; line-height: 5mm;">';
                        $html .= '<div class="tooltip" style="display: block; position: absolute; top: -20px; left: 20px; background-color: #fff; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">' . $value['name'] . '</div>';

                        $html .= '</div>';
                    }
                }

                // Close containers
                $html .= '</div>';
                $html .= '</div>';
            }
        }

        $dompdf->loadHtml($html);

        $dompdf->render();
        $coverPdfContent = $dompdf->output();
        $filePath = storage_path('app/pdf/rr.pdf');
        file_put_contents($filePath, $coverPdfContent);
        $this->savePdf($html, 'hh.pdf');

        $logo = 'https://sosindi.com/IHM/public/assets/images/logo.png';
        $hazmets = Hazmat::withCount(['checkHasHazmats as check_type_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->withCount(['checkHasHazmatsSample as sample_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->withCount(['checkHasHazmatsVisual as visual_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->get();



        $ChecksList = Deck::with(['checks.check_hazmats'])->where('project_id', $project_id)->get();

        $lebResult = LabResult::with(['check', 'hazmat'])->where('project_id', $project_id)->where('type', 'Contained')->orwhere('type', 'PCHM')->get();
        $attechments = Attechments::where('project_id', $project_id)->where('attachment_type', 'shipPlan')->get();
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
                'defaultPagebreakType' => 'avoid'
            ]);
            $mpdf->defaultPageNumStyle = '1';
            $mpdf->SetDisplayMode('fullpage');
            $pageCount = $mpdf->setSourceFile(storage_path('app/pdf/rr.pdf'));

            // Add each page of the Dompdf-generated PDF to the mPDF document

            $mpdf->use_kwt = true;
            $mpdf->defaultPageNumStyle = '1';
            $mpdf->SetDisplayMode('fullpage');

            // Define header content with logo
            $header = '
            <table width="100%" style="border-bottom: 1px solid #000000; vertical-align: middle; font-family: serif; font-size: 9pt; color: #000088;">
                <tr>
                    <td width="10%"><img src="' . $logo . '" width="50" /></td>
                    <td width="80%" align="center">' . $projectDetail['ship_name'] . '</td>
                    <td width="10%" style="text-align: right;">{DATE j-m-Y}</td>
                </tr>
            </table>';

            // Define footer content with page number
            $footer = '
            <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000;">
                <tr>
                    <td width="33%">' . $projectDetail['ihm_table'] . '</td>
                    <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
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
            $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);


            $mpdf->WriteHTML(view('report.cover', compact('projectDetail')));
            $mpdf->TOCpagebreak();

            $mpdf->TOCpagebreakByArray([
                'links' => true,
                'toc-preHTML' => '',
                'toc-bookmarkText' => 'Table of Contents',
                'level' => 0,
                'page-break-inside' => 'avoid',
                'suppress' => false, // This should prevent a new page from being created before and after TOC
                'toc-resetpagenum' => 1
            ]);

            $mpdf->WriteHTML(view('report.introduction', compact('hazmets', 'projectDetail')));
            $mpdf->AddPage('L'); // Set landscape mode for the inventory page
            $totalPages = $mpdf->page;

            $mpdf->WriteHTML(view('report.Inventory', compact('filteredResults1', 'filteredResults2', 'filteredResults3', 'decks')));

            for ($i = 1; $i <= $pageCount; $i++) {
                $mpdf->AddPage();
                $templateId = $mpdf->importPage($i);
                $mpdf->useTemplate($templateId);
            }
            $mpdf->AddPage('p');
            $mpdf->WriteHTML(view('report.development', compact('projectDetail', 'attechments', 'ChecksList', 'foundItems')));
            $mpdf->WriteHTML(view('report.IHM-VSC', compact('projectDetail')));


            $titleHtml = '<h2 style="text-align:center">Leb Result</h2>';

            $filePath =  public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb1LaboratoryResult1'];
            if (file_exists($filePath) && @$projectDetail['leb1LaboratoryResult1']) {
                $this->mergePdf($filePath,$titleHtml,$mpdf);
               
            }
            $filePath1 =  public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb1LaboratoryResult2'];
            if (file_exists($filePath1) && @$projectDetail['leb1LaboratoryResult2']) {
                $this->mergePdf($filePath1,null,$mpdf);
               
            }
            $filePath2 =  public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb2LaboratoryResult1'];
            if (file_exists($filePath2) && @$projectDetail['leb2LaboratoryResult1']) {
                $this->mergePdf($filePath2,null,$mpdf);
               
            }
            $filePath3 =  public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb2LaboratoryResult2'];
            if (file_exists($filePath) && @$projectDetail['leb2LaboratoryResult2']) {
                $this->mergePdf($filePath3,null,$mpdf);
               
            }




            $mpdf->Output('project_report.pdf', 'I');
            // Output the PDF
            $mpdf->Output('project_report.pdf', 'I');
        } catch (\Mpdf\MpdfException $e) {
            // Handle mPDF exception
            echo $e->getMessage();
        }
    }
    protected function mergePdf($filePath,$title,$mpdf){
        $mpdf->setSourceFile($filePath);

        $pageCount = $mpdf->setSourceFile($filePath);
        for ($i = 1; $i <= $pageCount; $i++) {

            $mpdf->AddPage();

            $templateId = $mpdf->importPage($i);
            if ($i === 1 && @$title) {
                $mpdf->WriteHTML($title);
                $mpdf->useTemplate($templateId, null, 40, null, null);
            } else {
                $mpdf->useTemplate($templateId);
            }
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

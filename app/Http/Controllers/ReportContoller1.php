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
  
        $pageNumbers = [];
        $currentPageNumber = 1;

        // Initialize Dompdf
        $pdfView = ['cover', 'introduction', 'Inventory','development'];
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

        $projectDetail = Projects::find($project_id);
        $imageData = file_get_contents($projectDetail['image']);
        $logo = 'https://sosindi.com/IHM/public/assets/images/logo.png';

        // Encode the image data to base64
        // Encode the image data to base64
        foreach ($pdfView as $pdf) {
            $options = new Options();
            $dompdf = new Dompdf($options);

            // Render and save PDF for cover page
            if ($pdf == 'introduction') {
                $dompdf->setPaper('A4', 'portrait');
                $coverHtml = view('report.' . $pdf, compact('hazmets', 'projectDetail'))->render();
            } else if ($pdf == 'Inventory') {
                $dompdf->setPaper('A4', 'landscape');

                $coverHtml = view('report.' . $pdf, compact('filteredResults1', 'filteredResults2', 'filteredResults3'))->render();
            } else {
                $coverHtml = view('report.' . $pdf, compact('projectDetail'))->render();
            }
            $dompdf->loadHtml($coverHtml);

            $dompdf->render();
            $coverPdfContent = $dompdf->output();

            $this->savePdf($coverPdfContent, $pdf . '.pdf');
            $tempPdf = new Fpdi();
            $pageCount = $tempPdf->setSourceFile(storage_path('app/pdf/' . $pdf . '.pdf'));
            $pageNumbers[$pdf] = $currentPageNumber + 1;
            $currentPageNumber += $pageCount;
        }

        /* for genrate Index code */
        $indexTableHtml = view('report.indexTable', compact('pageNumbers'))->render();
        $options = new Options();
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($indexTableHtml);
        $dompdf->render();
        $indexPdfContent = $dompdf->output();
        $filePath = storage_path('app/pdf/indexTable.pdf');
        file_put_contents($filePath, $indexPdfContent);

        // Merge PDFs including the index table
        $pdf = new Fpdi();
        $pdfFolderPath = storage_path('app/pdf');
        $inserted = array(1 => 'indexTable');

        array_splice($pdfView, 1, 0, $inserted);
        /* End code for index merege */

        $pdf = new Fpdi();
        $pdf->AliasNbPages();

        // Specify the folder where PDF files are stored
        $pdfFolderPath = storage_path('app/pdf');

        // Get the list of PDF files in the folder
        $pdfFiles = glob($pdfFolderPath . '/*.pdf');
        // Add each PDF file to the merged PDF
        $pageCountno = 0;


        foreach ($pdfView as $pdfFile1) {
            $pdfFile =  storage_path('app/pdf/' . $pdfFile1 . '.pdf');
            // Add a new page to the merged PDF
            $link = $pdf->AddLink();


            // Import the current page from the source PDF
            $pageCount = $pdf->setSourceFile($pdfFile);

            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                $pageCountno += 1;


                // Import the current page from the source PDF
                $templateId = $pdf->importPage($pageNumber);
                $size = $pdf->getTemplateSize($templateId);
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

                //set margins
                $pdf->SetMargins(1, 4, 1, 1, true);

                //set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, 3);

                $pdf->AddPage($orientation);
               

                $pdf->useTemplate($templateId);

                //use header
                $pdf->SetY(10); // Adjust Y position to set the header at the top
                $pdf->SetFont('Arial', 'B', 12);
                $pdfWidth = $pdf->GetPageWidth();

                // Get the width of the image
                $imageWidth = 20; // Assuming the image width is 30 units

                // Calculate the X coordinate to place the image on the right side
                $imageX = $pdfWidth - $imageWidth - 15; // Assuming a margin of 10 units from the right side

                // Add the image to the PDF
                $pdf->Image($logo, $imageX, 10, 30); // Adjust the image path and size as needed
                // Use the footer page

                $pdf->Ln(20);

                $pdf->SetFont('Arial', 'I', 8);
                $pdf->SetLeftMargin(10); // Adjust left margin as needed
                $pdf->SetRightMargin(10); // Adjust right margin as needed
                // Add the text to the PDF
                $pdf->SetY(-15);
                $pdf->Cell(0, 10, 'Page ' . $pageCountno, 0, 0, 'R');

                $pdf->SetY(-15);
                $pdf->Cell(0, 10, $projectDetail['ihm_table'], 0, 0, 'L');
                 // Title
                 $link = $pdf->AddLink();
                 $pdf->Write(5,'here',$link);
                // Line break

                //  unlink(storage_path('app/pdf/'.$pdfFile1.'.pdf'));

            }
        }

        // Output the merged PDF
        $mergedPdfFilePath = storage_path('app/pdf/merged.pdf');
        $pdfOutput = $pdf->Output('F', $mergedPdfFilePath); // Capture the PDF content as a string

        // return response($pdfOutput, 200)
        //     ->header('Content-Type', 'application/pdf')
        //     ->header('Content-Disposition', 'inline; filename="merged.pdf"');

        // return 'PDF files merged successfully!';

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

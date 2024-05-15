<?php

namespace App\Http\Controllers;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

use App\Exports\MultiSheetExport;
use App\Models\CheckHasHazmat;
use App\Models\Checks;
use App\Models\User;
use App\Models\Hazmat;
use App\Models\Projects;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Crypt;
use setasign\Fpdi\Fpdi;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Imagick;
use Spatie\PdfToImage\Pdf;

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
    public function genratePdf1()
    {
        $pdf = new Fpdi('L');

        // Load the existing PDF file
        $pdfFile = public_path('images/attachment/1/IAPP Cert.pdf');
        $pdf->setSourceFile($pdfFile);

        // Get the total number of pages in the PDF
        $totalPages = $pdf->setSourceFile($pdfFile);
        $pageCount = $pdf->setSourceFile($pdfFile);

        // Iterate through each page and import them into the new PDF
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Add a new page to the PDF
            $pdf->AddPage();

            // Import the current page from the source PDF
            $templateId = $pdf->importPage($pageNo);

            // Use the imported page
            $pdf->useTemplate($templateId, null, null, null, null, true);
        }

        $pdfFile = public_path('images/attachment/1/PDF-Home Decor1714742328.pdf');
        $pdf->setSourceFile($pdfFile);

        // Get the total number of pages in the PDF
        $totalPages = $pdf->setSourceFile($pdfFile);
        $pageCount = $pdf->setSourceFile($pdfFile);

        // Iterate through each page and import them into the new PDF
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Add a new page to the PDF
            $pdf->AddPage();

            // Import the current page from the source PDF
            $templateId = $pdf->importPage($pageNo);

            // Use the imported page
            $pdf->useTemplate($templateId, null, null, null, null, true);
        }


        // Output the merged PDF
        $pdfFilePath = public_path('merged.pdf');
        $pdf->Output('F', $pdfFilePath);
        // Return a binary response with the merged PDF file as a download
        return new BinaryFileResponse(public_path('merged.pdf'));
    }
    public function genratePdf()
    {

        $fileArray = [
            '7. IHM Expert Training Certificate- Praveen Raj.pdf',
            'IAPP Cert.pdf',
        ];

        // Temporary directory for storing converted images
        $tempDir = sys_get_temp_dir();

        // Initialize FPDI
        $pdf = new Fpdi('L');

        foreach ($fileArray as $file) {
            $filePath = public_path('images/' . $file);
            $pdfContent = file_get_contents($filePath);
        
            // If PDF is not encrypted, add its content
            if (strpos($pdfContent, '/Encrypt') === false) {
                // Add pages directly
                $pageCount = $pdf->setSourceFile($filePath);
                for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                    $templateId = $pdf->importPage($pageNumber);
                    $pdf->AddPage();
                    $pdf->useTemplate($templateId);
                }
            } else {
                // Handle encrypted PDFs (if needed)
                $pdf1 = new Pdf($filePath);
                $pdf1->saveImage($tempDir);
                $imageFiles = glob($tempDir . '/*.{jpg,png}', GLOB_BRACE);
        
                // Add each image as a page to the merged PDF
                foreach ($imageFiles as $imageFile) {
                    $pdf->AddPage();
                    $pdf->Image($imageFile, 0, 0, 210, 297); // Assuming A4 size, adjust as needed
                }
                
                // Clean up temporary image files
                foreach ($imageFiles as $imageFile) {
                    unlink($imageFile);
                }    
            }
        }
        
        // Output merged PDF
        // foreach ($fileArray as $file) {
        //     $filePath = public_path('images/' . $file);
        //     // Read the PDF file
        //     $pdfContent = file_get_contents($filePath);

        //     // Check if the PDF is password protected
        //     if (strpos($pdfContent, '/Encrypt') === false) {
        //         // If not password protected, proceed without modification
        //         $pdfContent = preg_replace("/\/Encrypt[\s\n]*\{[^\}]+\}/", '', $pdfContent);
        //         file_put_contents($filePath, $pdfContent);
        //         continue;
        //     }

        //     // Load PDF without password protection
        //     $pdfContent = preg_replace("/\/Encrypt[\s\n]*\{[^\}]+\}/", '', $pdfContent);

        //     // Save the PDF without password protection
        //     file_put_contents($filePath, $pdfContent);
        // }
        // $pdf = new Fpdi('L');

        // // Load the existing PDF file
        // $pdfFile = public_path('images/attachment/1/IAPP Cert.pdf');
        // $pdf->setSourceFile($pdfFile);

        // // Get the total number of pages in the PDF
        // $totalPages = $pdf->setSourceFile($pdfFile);
        // $pageCount = $pdf->setSourceFile($pdfFile);

        // // Iterate through each page and import them into the new PDF
        // for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        //     // Add a new page to the PDF
        //     $pdf->AddPage();

        //     // Import the current page from the source PDF
        //     $templateId = $pdf->importPage($pageNo);

        //     // Use the imported page
        //     $pdf->useTemplate($templateId, null, null, null, null, true);
        // }

        // $pdfFile = public_path('images/7. IHM Expert Training Certificate- Praveen Raj.pdf');
        // $pdf->setSourceFile($pdfFile);

        // // Get the total number of pages in the PDF
        // $totalPages = $pdf->setSourceFile($pdfFile);
        // $pageCount = $pdf->setSourceFile($pdfFile);

        // // Iterate through each page and import them into the new PDF
        // for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        //     // Add a new page to the PDF
        //     $pdf->AddPage();

        //     // Import the current page from the source PDF
        //     $templateId = $pdf->importPage($pageNo);

        //     // Use the imported page
        //     $pdf->useTemplate($templateId, null, null, null, null, true);
        // }


        // // Output the merged PDF
        // $pdfFilePath = public_path('merged.pdf');
        // $pdf->Output('F', $pdfFilePath);
        // // Return a binary response with the merged PDF file as a download
        // return new BinaryFileResponse(public_path('merged.pdf'));
        // $filePath = public_path('images/7. IHM Expert Training Certificate- Praveen Raj.pdf');
        // $pdf = new Pdf($filePath);

        // $password = '123456';
        // $userPassword = '123456a9';

        // $result = $pdf->allow('AllFeatures')

        //                 ->passwordEncryption(128)
        //                 ->saveAs($filePath);

        // if ($result === false) {
        //     $error = $pdf->getError();
        // }

        // return response()->download($filePath);



        // Output file path for the merged PDF

        // Return a response to download the merged PDF
        //  return response()->download($outputFilePath)->deleteFileAfterSend(true);
    }
}

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
use setasign\Fpdi\Fpdi;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\File;
use \ConvertApi\ConvertApi;
use FPDF;
use ZendPdf\PdfDocument;
use ZendPdf\Page;



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
        $pdfFiles = ['IAPP Cert.pdf', 'MT SONG Lab Report.pdf'];
        $outputPdf = 'Combined.pdf';
              $outputPath = public_path('merged_pdfs/' . $outputPdf);

        $pdf1Path= public_path('images/attachment/1/' . $pdfFiles[0]);
        $pdf2Path= public_path('images/attachment/1/' . $pdfFiles[1]);
        $pdftkPath = "C:\\Program Files\\pdftk_free-2.02-win-setup.exe"; // Update this with the full path to pdftk.exe

        $process = new Process([$pdftkPath, $pdf1Path, $pdf2Path, 'cat', 'output', $outputPath]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        // foreach ($pdf2->pages as $page) {
        //     $clonedPage = clone $page;
        //     $pdf1->pages[] = $clonedPage;
        // }
      //  $combinedPdfPath = public_path('merged_pdfs/' . $outputPdf);

       // $pdf1->save($combinedPdfPath);
        exit();
        // Initialize a new PDF document
        $combinedPdf = new PdfDocument();
        
        // Iterate through each PDF file
        foreach ($pdfFiles as $pdfFile) {
            // Get the full path to the PDF file
            $pdfPath = public_path('images/attachment/1/' . $pdfFile);
        
            // Load the PDF file
                  $pdf = new Fpdi('L');
            $pageCount = $pdf->setSourceFile($pdfPath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // Add a new page to the PDF
                $pdf->AddPage();
    
                // Import the current page from the source PDF
                $templateId = $pdf->importPage($pageNo);
    
                // Use the imported page
                $pdf->useTemplate($templateId, null, null, null, null, true);
            }
        }
        
        // Save the combined PDF document to a file
        $combinedPdfPath = public_path('merged_pdfs/' . $outputPdf);
        $combinedPdf->Output('F', $combinedPdfPath);
    //     // Initialize variables to hold combined content
    //     $combinedBinary = '';
        
    //     // Iterate through each PDF file
    //     foreach ($files as $pdfFile) {
    //         // Get the path of the PDF file
    //         $pdfPath = public_path('images/attachment/1/' . $pdfFile);
        
    //         // Create an instance of FPDI
    //         $pdf = new Fpdi();
        
    //         // Get the number of pages in the PDF
    //         $pageCount = $pdf->setSourceFile($pdfPath);
        
    //         // Iterate through each page of the PDF
    //         for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    //             // Add a new page to the combined PDF
    //             $pdf->AddPage();
        
    //             // Import the current page from the source PDF
    //             $templateId = $pdf->importPage($pageNo);
        
    //             // Use the imported page
    //             $pdf->useTemplate($templateId);
    //         }
        
    //         // Get the content of the PDF after importing pages
    //         $combinedBinary .= $pdf->Output('', 'S');
    //     }
        
    //     // Perform string replacement if needed
    //     if (strpos($combinedBinary, "Hello You") === false) {
    //         $combinedBinary = str_replace("Go Away", "Hello You", $combinedBinary);
    //     }
        
    //     // Save the combined binary data to a temporary file
    //     $combinedPdfPath = storage_path('app/temp/combined.pdf');
    //     file_put_contents($combinedPdfPath, $combinedBinary);
        // foreach ($files as $filename) {
        //     $pdfPath = public_path('images/attachment/1/' . $filename);
        //     $file = file_get_contents( $pdfPath);
        //     $combinedBase64 = base64_encode( $file);
        //     $combinedBinary = base64_decode($combinedBase64);
        //     if(strpos($file, "Hello You") !== false){
        //         echo "Already Replaced";
        //     }
        //     else {
        //         $combinedPdfPath = storage_path('app/temp/combined.pdf');
        //      //   $str = str_replace("Go Away", "Hello You", $file);
        //         file_put_contents( $combinedPdfPath, $combinedBinary); 
        //         echo "done";
        //     }
        // }
        //  $dompdf = new Dompdf();
        // $dompdf->loadHtml('<h1>Merged PDF</h1>');
        // $dompdf->setPaper('A4', 'portrait');
        // $dompdf->loadHtml('<h1>Merged PDF</h1>');
        // $dompdf->loadHtmlFile($combinedPdfPath); // Load HTML from the combined PDF file
        // $dompdf->render();
        // return $dompdf->stream('merged_pdf.pdf');
        // $files = ['IAPP Cert.pdf', 'MT SONG Lab Report.pdf'];
        // $combinedBase64 = '';

        // // Convert each PDF file to base64 encoding and concatenate
        // foreach ($files as $file) {
        //     $pdfPath = public_path('images/attachment/1/' . $file);
        //     $combinedBase64 .= base64_encode(file_get_contents($pdfPath));
        // }

        // // Decode the combined base64 string back to binary data
        // $combinedBinary = base64_decode($combinedBase64);

        // // Save the combined binary data to a temporary file
        // $combinedPdfPath = storage_path('app/temp/combined.pdf');
        // file_put_contents($combinedPdfPath, $combinedBinary);

        // // Generate PDF using Dompdf
        // $dompdf = new Dompdf();
        // $dompdf->loadHtml('<h1>Merged PDF</h1>');
        // $dompdf->setPaper('A4', 'portrait');
        // $dompdf->loadHtml('<h1>Merged PDF</h1>');
        // $dompdf->loadHtmlFile($combinedPdfPath); // Load HTML from the combined PDF file
        // $dompdf->render();

        // // Return the generated PDF
        // return $dompdf->stream('merged_pdf.pdf');


        // Return the generated PDF

    }
    
}

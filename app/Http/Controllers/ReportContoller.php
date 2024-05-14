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

use Imagick; 

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
         $pdf = new Fpdi('L');

        
      // Add an initial page to the new PDF document
$pdf->AddPage();

$filesToMerge = [
    '1.pdf',
    'IAPP Cert.pdf',
];

// Initialize Imagick
$imagick = new Imagick();

foreach ($filesToMerge as $pdfUrl) {
    // Fetch the PDF file contents
    $pdfPath = asset('images'."/".$pdfUrl);
    $pdfContents = file_get_contents($pdfPath);

    // Create a temporary file to store the PDF contents
    $tempPdfFile =  $pdfPath;
    //echo  $tempPdfFile;
    file_put_contents($tempPdfFile, $pdfContents);

    // Set the source file
    $pdf->setSourceFile($tempPdfFile);

    // Get the total number of pages in the PDF
    $pageCount = $pdf->setSourceFile($tempPdfFile);

    // Iterate through each page and import them into the new PDF
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        // Import the current page from the source PDF
        $templateId = $pdf->importPage($pageNo);

        // Add a new page to the new PDF document
        $pdf->AddPage();

        // Use the imported page
        $pdf->useTemplate($templateId);

        // Get the dimensions of the page
        $size = $pdf->getTemplateSize($templateId);
     $width = isset($size['w']) ? $size['w'] : 0;
$height = isset($size['h']) ? $size['h'] : 0;

        // Create a new Imagick object for the current page
        $imagickPage = new Imagick();
        $imagickPage->setResolution(150, 150); // Set resolution for better quality

        // Convert PDF page to image
        $imagickPage->readImage('pdf:'.$tempPdfFile.'['.$pageNo.']');
        $imagickPage->setImageFormat('png'); // You can change the format as needed
        $imagickPage->setImageCompressionQuality(100); // Adjust compression quality as needed

        // Append the converted page image to the Imagick object
        $imagick->addImage($imagickPage);
    }

    // Clean up temporary file
    unlink($tempPdfFile);
}

// Merge all page images into one image
$imagick->resetIterator();
$combinedImage = $imagick->appendImages(true);

// Set the image format (e.g., JPEG)
$combinedImage->setImageFormat('jpeg');

// Save or output the combined image
$combinedImage->writeImage(public_path('merged_image.jpg'));

// Clean up resources
$imagick->clear();
$imagick->destroy();
        // $pdf = new Fpdi('L');

        // $filesToMerge = [
        //     public_path('images/MAERSK PATRAS_Test Report.pdf'),
        //     public_path('images/attachment/1/IAPP Cert.pdf'),
        // ];
        
        // foreach ($filesToMerge as $pdfFile) {
        //     // Set the source file
        //     $pdf->setSourceFile($pdfFile);
        
        //     // Get the total number of pages in the PDF
        //     $pageCount = $pdf->setSourceFile($pdfFile);
        
        //     // Iterate through each page and import them into the new PDF
        //     for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        //         // Add a new page to the PDF
        //         $pdf->AddPage();
        
        //         // Import the current page from the source PDF
        //         $templateId = $pdf->importPage($pageNo);
        
        //         // Use the imported page
        //         $pdf->useTemplate($templateId, null, null, null, null, true);
        //       //  $pdf->Rect(0, $pdf->GetPageHeight() - 15, $pdf->GetPageWidth(), 15, 'F', [], [255, 255, 255]);

        //     }
        // }
        
        // // Output the merged PDF
        // $pdfFilePath = public_path('merged.pdf');
        // $pdf->Output('F', $pdfFilePath);
        
        // // Return a binary response with the merged PDF file as a download
        // return response()->download($pdfFilePath)->deleteFileAfterSend(true);
    }
    
}

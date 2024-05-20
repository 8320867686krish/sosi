<?php

namespace App\Http\Controllers;


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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Imagick;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

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
    public function genratePdf($project_id)
    {
        // Initialize FPDI instance
        $pdf = new Fpdi('L');

        // Initialize Dompdf instance
        $dompdf = new Dompdf();

        // Load HTML content with a header
        $htmlContent = view('pdf')->render();

        // Load HTML content into Dompdf
        $dompdf->loadHtml($htmlContent);

        // Set paper size and orientation (adjust as needed)
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Save the rendered PDF
        $dompdfOutput = $dompdf->output();

        // Import each page of the generated PDF into FPDI
        $pdfData = $dompdfOutput;
        $pageCount = $pdf->setSourceFile(StreamReader::createByString($pdfData));
        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $templateId = $pdf->importPage($pageNumber);
            $pdf->AddPage();
            $pdf->useTemplate($templateId, ['adjustPageSize' => true]);


        }
        $pdfFolder = public_path('images/attachment/'.$project_id."/");
        $pdfFiles = glob($pdfFolder . '*.pdf');
        foreach ($pdfFiles as $pdfFile) {
            // Set source file for FPDI
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
                $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

                $pdf->SetFont('Arial', 'I', 8);

            }
        }
        // Output the merged PDF
        $pdfFilePath = public_path('merged_with_header.pdf');
        $pdf->Output('F', $pdfFilePath);

        echo 'PDF generated successfully!';
    }
}

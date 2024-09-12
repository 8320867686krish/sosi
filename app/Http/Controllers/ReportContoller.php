<?php

namespace App\Http\Controllers;

use App\Exports\MultiSheetExport;
use App\Models\Attechments;
use App\Models\CheckHasHazmat;
use App\Models\Checks;
use App\Models\Deck;
use App\Models\Hazmat;
use App\Models\LabResult;
use App\Models\Projects;
use App\Models\ReportMaterial;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Smalot\PdfParser\Parser;

ini_set("pcre.backtrack_limit", "1000000");
ini_set('exif.decode_jpeg', '0');


class ReportContoller extends Controller
{
    protected $pdfGenerator;


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
        $imo_number = $project["imo_number"];
  $project_no = $project['project_no'];
        $safeProjectNo = str_replace('/', '_', $project_no);

        if ($isSample) {
            $checks = $checks->where('type', 'sample');

            $filename = "Lab-Test-List-{$safeProjectNo}" . "." . $fileExt;
        } else {
            $filename = "VSCP-{$safeProjectNo}" . "." . $fileExt;
        }
       

        $checks = $checks->get();

        return Excel::download(new MultiSheetExport($project, $hazmats, $checks, $isSample), $filename, $exportFormat);
    }

    public function summeryReport($post)
    {
        $project_id = $post['project_id'];
        $version = $post['version'];
        $date = date('d-m-Y', strtotime($post['date']));

        $projectDetail = Projects::with('client')->find($project_id);
        if (!$projectDetail) {
            die('Project details not found');
        }
        $options = new Options();
        $dompdf = new Dompdf($options);
        $html = '';
        $logo = 'https://sosindi.com/IHM/public/assets/images/logo.png';
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
        $decks = Deck::with(['checks' => function ($query) {
            $query->whereHas('labResults', function ($query) {
                $query->where('type', 'PCHM')->orWhere('type', 'Contained');
            });
        }])->where('project_id', $project_id)->get();

        try {
            // Create an instance of mPDF with specified margins
            $mpdf = new Mpdf([
                'mode' => 'c',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 20,
                'margin_header' => 0,
                'margin_footer' => 10,
                'defaultPagebreakType' => 'avoid',
                'imageProcessor' => 'GD', // or 'imagick' if you have Imagick installed
                'jpeg_quality' => 75, // Set the JPEG quality (0-100)
                'shrink_tables_to_fit' => 1, // Shrink tables to fit the page width
                'tempDir' => __DIR__ . '/tmp', // Set a temporary directory for mPDF


                'allow_output_buffering' => true,
            ]);
            $mpdf->defaultPageNumStyle = '1';
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetCompression(true);

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
                    <td width="10%" style="text-align: right;">' . $projectDetail['project_no'] . '<br/>' . $date . '</td>
                </tr>
            </table>';

            // Define footer content with page number
            $footer = '
            <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000;">
                <tr>
                    <td width="33%" style="text-align: left;">' . $projectDetail['ihm_table'] . 'Summary</td>
                    <td width="33%" style="text-align: center;">Revision:' . $version . '</td>
                    <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
                </tr>
            </table>';
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);

            $stylesheet = file_get_contents('public/assets/mpdf.css');
            $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
            $summery = 'Summary';
            $mpdf->WriteHTML(view('report.cover', compact('projectDetail','summery')));
            $mpdf->WriteHTML(view('report.shipParticular', compact('projectDetail')));
            $mpdf->AddPage('L'); // Set landscape mode for the inventory page

            $mpdf->WriteHTML(view('report.Inventory', compact('filteredResults1', 'filteredResults2', 'filteredResults3', 'decks')));
            foreach ($decks as $key => $value) {
                if (count($value['checks']) > 0) {
                    $html = $this->drawDigarm($value);
                    $fileNameDiagram = $this->genrateDompdf($html, 'le');
                    //    $mpdf = new Mpdf(['orientation' => 'L']); // Ensure landscape mode
                    $mpdf->setSourceFile($fileNameDiagram);

                    $pageCount = $mpdf->setSourceFile($fileNameDiagram);
                    for ($i = 1; $i <= $pageCount; $i++) {

                        $mpdf->AddPage('L');
                        if ($key == 0) {
                            $mpdf->WriteHTML('<h3 style="font-size:14px">2.1 Location Diagram of Contained HazMat & PCHM</h3>');
                        }
                        $mpdf->WriteHTML('<h5 style="font-size:14px;">Area: ' . $value['name'] . '</h5>');

                        $templateId = $mpdf->importPage($i);
                        $mpdf->useTemplate($templateId, null, null, $mpdf->w, null); // Use the template with appropriate dimensions

                        //  $mpdf->useTemplate($templateId, 0, 5, null, null);
                    }
                }
            }
            $safeProjectNo = str_replace('/', '_', $projectDetail['project_no']);
            $fileName = "summary_" . $safeProjectNo . '.pdf';

            $filePath = public_path('pdfs1/' . $fileName); // Adjust the directory and file name as needed
            $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
            $response = response()->download($filePath, $fileName)->deleteFileAfterSend(true);
            $response->headers->set('X-File-Name', $fileName);
            return $response;
        } catch (\Mpdf\MpdfException $e) {
            // Handle mPDF exception
            echo $e->getMessage();
        }
    }

    public function genratePdf(Request $request)
    {


        $post = $request->input();

        $project_id = $post['project_id'];
        $version = $post['version'];
        $date = date('d-m-Y', strtotime($post['date']));
        $projectDetail = Projects::with('client')->find($project_id);
        if ($post['action'] == 'summery') {
         return  $this->summeryReport($post);
        }

        $hazmets = Hazmat::withCount(['checkHasHazmats as check_type_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->withCount(['checkHasHazmatsSample as sample_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->withCount(['checkHasHazmatsVisual as visual_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->get();

        // $decksval = Deck::with(['checks' => function ($query) {
        //     $query->whereHas('check_hazmats', function ($query) {
        //         $query->where('type', 'PCHM')->orWhere('type', 'Contained');
        //     });
        // }])->where('project_id', $project_id)->get();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $logo = 'https://sosindi.com/IHM/public/assets/images/logo.png';

        $lebResultAll = LabResult::with(['check', 'hazmat'])->where('project_id', $project_id)->where('type', 'Contained')->orwhere('type', 'PCHM')->get();


        $attechments = Attechments::where('project_id', $project_id)->where('attachment_type', '!=', 'shipBrifPlan')->get();
        $brifPlan = Attechments::where('project_id', $project_id)->where('attachment_type', '=', 'shipBrifPlan')->first();
        $ChecksList = Deck::with(['checks.check_hazmats.hazmat'])
            ->where('project_id', $project_id)
            ->get();

        $ChangeCheckList = Checks::with('labResultsChange.hazmat')->where('markAsChange',1)
            ->where('project_id', $project_id)
            ->get();

        $decks = Deck::with(['checks' => function ($query) {
            $query->whereHas('labResults', function ($query) {
                $query->where('type', 'PCHM')->orWhere('type', 'Contained');
            });
        }])->where('project_id', $project_id)->get();
        $ChecksListImage = Checks::with(['check_image', 'labResults'])->where('project_id', $project_id)->get();

        $sampleImage = $ChecksListImage->filter(function ($item) {
            return $item->type == 'sample';
        });

        $visualImage = $ChecksListImage->filter(function ($item) {
            return $item->type == 'visual';
        });
        if (@$brifPlan['documents']) {
            $brifimage = public_path('images/attachment') . "/" . $projectDetail['id'] . "/" . $brifPlan['documents'];
        } else {
            $brifimage = '';
        };
        $attechmentsResult = $attechments->filter(function ($item) {
            return $item->attachment_type == 'shipPlan';
        });
        $filteredResults1 = $lebResultAll->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-1';
        });

        $filteredResults2 = $lebResultAll->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-2';
        });
        $filteredResults3 = $lebResultAll->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-3';
        });
        if ($projectDetail['ihm_table'] == 'IHM Part 1') {
            $report_materials = ReportMaterial::where('project_id', $project_id)->get()->toArray();
            $foundItems = [];

            foreach ($report_materials as $value) {
                $index = array_search($value['structure'], array_column($report_materials, 'structure'));
                if ($index !== false) {
                    $foundItems[$value['structure']] = $report_materials[$index];
                }
            }
        } else {
            $reportMaterials = ReportMaterial::where('project_id', $project_id)->where('structure', 'gapAnalysis')->first()->toArray();
        }

        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 20,
            'margin_header' => 0,
            'margin_footer' => 10,
            'defaultPagebreakType' => 'avoid',
            'imageProcessor' => 'GD', // or 'imagick' if you have Imagick installed
            'jpeg_quality' => 75, // Set the JPEG quality (0-100)
            'shrink_tables_to_fit' => 1, // Shrink tables to fit the page width
            'tempDir' => __DIR__ . '/tmp', // Set a temporary directory for mPDF
            'default_font' => 'dejavusans',


            'allow_output_buffering' => true,
        ]);
        $mpdf->SetCompression(true);

        $mpdf->use_kwt = true;

        $mpdf->defaultPageNumStyle = '1';
        $mpdf->SetDisplayMode('fullpage');

        // Define header content with logo
        $header = '
        <table width="100%" style="vertical-align: middle; font-family: serif; font-size: 9pt; color: #000088;">
            <tr>
                <td width="10%"><img src="' . $logo . '" width="50" /></td>
                <td width="80%" align="center">' . $projectDetail['ship_name'] . '</td>
                <td width="10%" style="text-align: right;">' . $projectDetail['project_no'] . '<br/>' . $date . '</td>
            </tr>
        </table>';

        // Define footer content with page number
        $footer = '
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000;">
            <tr>
                <td width="33%" style="text-align: left;">' . $projectDetail['ihm_table'] . '</td>
                <td width="33%" style="text-align: center;">Revision:' . $version . '</td>
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
            'toc-resetpagenum' => 1,
        ]);

        if ($projectDetail['ihm_table'] == 'IHM Part 1') {
            $mpdf->WriteHTML(view('report.introduction', compact('hazmets', 'projectDetail')));
        } else {
            $mpdf->WriteHTML(view('report.gapAnaylisis', compact('hazmets', 'projectDetail')));
        }


        $mpdf->AddPage('L'); // Set landscape mode for the inventory page

        $mpdf->WriteHTML(view('report.Inventory', compact('filteredResults1', 'filteredResults2', 'filteredResults3')));
        $newDiagram = 0;
        foreach ($decks as $key => $value) {
            if (count($value['checks']) > 0) {
                $html = $this->drawDigarm($value);
                $fileNameDiagram = $this->genrateDompdf($html, 'le');
                $mpdf->setSourceFile($fileNameDiagram);

                $pageCount = $mpdf->setSourceFile($fileNameDiagram);
                for ($i = 1; $i <= $pageCount; $i++) {

                    $mpdf->AddPage('L');
                    if ($newDiagram  == 0) {
                        $mpdf->WriteHTML('<h3 style="font-size:14px">2.1 Location Diagram of Contained HazMat & PCHM</h3>');
                    }
                    $mpdf->WriteHTML('<h5 style="font-size:14px;">Area: ' . $value['name'] . '</h5>');
                    $templateId = $mpdf->importPage($i);
                    $mpdf->useTemplate($templateId, null, null, $mpdf->w, null); // Use the template with appropriate dimensions

                }
                $newDiagram++;
                unlink($fileNameDiagram);
            }
        }

        $mpdf->AddPage('p');
        if ($projectDetail['ihm_table'] == 'IHM Part 1') {
            $mpdf->WriteHTML(view('report.development', compact('projectDetail', 'attechmentsResult', 'foundItems')));
        } else {
            $mpdf->WriteHTML(view('report.gapdevelopment', compact('projectDetail', 'reportMaterials', 'attechmentsResult')));
        }

        foreach ($ChecksList as $key => $value) {
            $html = $this->drawDigarm($value);
            $fileNameDiagram = $this->genrateDompdf($html, 'le');
            $mpdf->setSourceFile($fileNameDiagram);
            $pageCount = $mpdf->setSourceFile($fileNameDiagram);
            for ($i = 1; $i <= $pageCount; $i++) {
                $mpdf->AddPage('L');
                if ($key == 0) {
                    $heading = '<h3 style="font-size:14px">3.4 VSCP Preparation.</h3>';

                    $mpdf->WriteHTML($heading);
                }
                $mpdf->WriteHTML('<h5 style="font-size:16px;">Area: ' . $value['name'] . '</h5>');

                $templateId = $mpdf->importPage($i);
                $mpdf->useTemplate($templateId, null, null, $mpdf->w, null); // Use the template with appropriate dimensions

            }
            $mpdf->AddPage('L');
            $mpdf->writeHTML(view('report.vscpPrepration', ['checks' => $value['checks'], 'name' => $value['name']]));
            unlink($fileNameDiagram);
        }
        $mpdf->AddPage('P');
        $mpdf->WriteHTML(view('report.IHM-VSC', compact('projectDetail', 'brifimage', 'lebResultAll')));
        $mpdf->AddPage('L');
        $mpdf->WriteHTML(view('report.VisualSamplingCheck', compact('ChecksList','ChangeCheckList')));

        $mpdf->WriteHTML(view('report.riskAssessments'));
        $sampleImageChunks = $sampleImage->chunk(50);
        foreach ($sampleImageChunks as $index => $chunk) {
            if ($index == 0) {
                $title = "Sample Records";
                $show = true;
            } else {
                $show = false;
            }
            $numberColoum = "Sample No";


            $html = view('report.sampleImage', compact('chunk', 'title', 'show','numberColoum'))->render();
            $mpdf->WriteHTML($html);
        }
        $sampleImageChunks = $visualImage->chunk(50);
        $k=0;
        foreach ($sampleImageChunks as $index => $chunk) {
            if ($k == 0) {
                $title = "Visual Records";
                $numberColoum = "Check No";
                $visualShow = true;
            }
            $k++;
            $html = view('report.visualImage', compact('chunk', 'title','visualShow','numberColoum'))->render();
            $mpdf->WriteHTML($html);
        }


        $titleattach = '<h2 style="text-align:center;font-size:14px;">Appendix-4 Supporting Documents/plans from Ship</h2>';
        $check_has_hazmats = CheckHasHazmat::where('project_id', $project_id)->get();

        $i = 0;
        foreach ($check_has_hazmats as $checkvalue) {
            $image = $checkvalue->image;

            if (@$image) {

                $imageValue = basename($checkvalue->image);
                if (@$imageValue) {
                    $i++;
                    $filePath = public_path('images/hazmat') . "/" . $projectDetail['id'] . "/" . $imageValue;
                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                    if (file_exists($filePath)) {
                        if ($i == 1) {
                            if ($fileExtension === 'pdf') {
                                $this->mergePdf($filePath, $titleattach, $mpdf);
                            } else {
                                $this->mergeImageToPdf($filePath, $titleattach, $mpdf);
                            }
                        } else {
                            if ($fileExtension === 'pdf') {
                                $this->mergePdf($filePath, null, $mpdf);
                            } else {
                                $this->mergeImageToPdf($filePath, null, $mpdf);
                            }
                        }
                    }
                }
            }

            $imageDoc = $checkvalue->doc;

            if (@$imageDoc) {
                $imageDocValue = basename($checkvalue->imadocge);
                if (@$imageDocValue) {
                    $filePathDoc = public_path('images/hazmat') . "/" . $projectDetail['id'] . "/" . $imageDocValue;
                    $fileExtension = strtolower(pathinfo($filePathDoc, PATHINFO_EXTENSION));

                    if (file_exists($filePathDoc)) {
                        if ($fileExtension === 'pdf') {
                            $this->mergePdf($filePathDoc, null, $mpdf);
                        } else {
                            $this->mergeImageToPdf($filePath, null, $mpdf);
                        }
                    }
                }
            }
        }
        $attachmentCount = 0;

        if (@$projectDetail['leb1LaboratoryResult1']) {


            $filePath = public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb1LaboratoryResult1'];
            if (file_exists($filePath) && @$projectDetail['leb1LaboratoryResult1']) {
                $attachmentCount++;
                $titleHtml = '<h2 style="text-align:center;font-size:13px;font-weight:bold;" id="labResult">Attachment ' . $attachmentCount . ' Lab Result</h2>';
                $this->mergePdf($filePath, $titleHtml, $mpdf);
            }
        }
        if (@$projectDetail['leb1LaboratoryResult2']) {

            $filePath1 = public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb1LaboratoryResult2'];
            if (file_exists($filePath1) && @$projectDetail['leb1LaboratoryResult2']) {
                $attachmentCount++;
                $titleHtml = '<h2 style="text-align:center;font-size:13px;padding-bottom:10px;font-weight:bold;" id="lebResult">Attachment ' . $attachmentCount . ' Lab Result</h2>';
                $this->mergePdf($filePath1, $titleHtml, $mpdf);
            }
        }

        if (@$projectDetail['leb2LaboratoryResult1']) {

            $filePath2 = public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb2LaboratoryResult1'];
            if (file_exists($filePath2) && @$projectDetail['leb2LaboratoryResult1']) {
                $attachmentCount++;
                $titleHtml = '<h2 style="text-align:center;font-size:14px;padding-bottom:10px;font-weight:bold;" id="lebResult">Attachment ' . $attachmentCount . ' Lab Result</h2>';
                $this->mergePdf($filePath2, $titleHtml, $mpdf);
            }
        }

        if (@$projectDetail['leb2LaboratoryResult2']) {

            $filePath3 = public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb2LaboratoryResult2'];
            if (file_exists($filePath) && @$projectDetail['leb2LaboratoryResult2']) {
                $attachmentCount++;
                $titleHtml = '<h2 style="text-align:center;font-size:14px;font-weight:bold;" id="lebResult">Attachment ' . $attachmentCount . ' Lab Result</h2>';
                $this->mergePdf($filePath3, $titleHtml, $mpdf);
            }
        }
        foreach ($attechments as $value) {


            $filePath = public_path('images/attachment') . "/" . $projectDetail['id'] . "/" . $value['documents'];
            if (file_exists($filePath) && @$value['documents']) {
                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $attachmentCount++;
                $titleattach = '<h2 style="text-align:center;font-size:14px;font-weight:bold;">Attachment ' . $attachmentCount . " " . $value['heading'] . '</h2>';

                if ($fileExtension === 'pdf') {
                    $this->mergePdf($filePath, $titleattach, $mpdf);
                } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $this->mergeImageToPdf($filePath, $titleattach, $mpdf);
                }
            }
        }
        $mpdf->WriteHTML('<div style="position:absolute;bottom:100px;"><table width="100%"><tr><td style="text-align:center">... End of the IHM Report...</td></tr></table></div>');
    
        $safeProjectNo = str_replace('/', '_', $projectDetail['project_no']);

        $fileName = $safeProjectNo . '.pdf';
        $filePath = public_path('pdf/' . $fileName); // Adjust the directory and file name as needed
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
        $response = response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        $response->headers->set('X-File-Name', $fileName);
        return $response;



        // return response()->make($mpdf->Output('project_report.pdf', 'D'), 200, [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'attachment; filename="project_report.pdf"'
        // ]);
    }


    public function genrateDomPdf($html, $page)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        if (@$page) {
            $dompdf->setPaper('A4', 'landscape');
        } else {
            $dompdf->setPaper('A4', 'portrait');
        }
        $dompdf->render();
        $mainContentPdf = $dompdf->output();
        $filename = "project" . uniqid() . "ab.pdf";
        $filePath = storage_path('app/pdf') . "/" . $filename;

        file_put_contents($filePath, $mainContentPdf);
        return $filePath;
    }

    public function drawDigarm($decks)
    {
        $i = 1;
        $html = "";
        $lineCss = 'position:absolute;background-color:#4052d6;border:solid #4052d6 1px;';
        $tooltipCss = 'position: absolute;background-color: #fff;border: 1px solid #4052d6;padding: 1px;border-radius: 2px;
                white-space: nowrap;z-index: 1;color:#4052d6;font-size:8px;text-align:center;';
        if (count($decks['checks']) > 0) {
            $chunks = array_chunk($decks['checks']->toArray(), 15);

            $k = 0;
            $gap = 1;
            $oddincreaseGap = 29;
            $evenincreaseGap = 30;
            foreach ($chunks as $chunkIndex => $chunk) {
                $imagePath = $decks['image'];
                $imageData = base64_encode(file_get_contents($imagePath));
                $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
                list($width, $height) = getimagesize($imagePath);
                if ($width >= 1000) {
                    $html .= "<div class='maincontnt next' style='display: flex; justify-content: center; align-items: center; flex-direction: column; height:100vh;'>";
                } else {
                    if ($height >= 500) {
                        // $image_width = $width;
                        $image_height = 400;
                        $image_width = ($image_height * $width) / $height;
                    } else {
                        $image_width = $width;
                    }
                    $leftPositionPixels = (1024 - $image_width) / 2;
                    $leftPositionPercent = ($leftPositionPixels / 1024) * 100;

                    $html .= "<div class='maincontnt next' style='display: flex; justify-content: center; align-items: center; flex-direction: column;margin-left:{$leftPositionPercent}%;'>";
                }


                $html .= '<div style="margin-top:20%;">';

                $html .= '<div class="image-container " id="imgc' . $i . '" style="position: relative;width: 100%; ">';
                $image_width  = 1024;

                if ($width > 1000) {
                    $image_height = ($image_width * $height) / $width;

                    $newImage = '<img src="' . $imageBase64 . '" id="imageDraw' . $i . '" style="width:' .  $image_width . 'px;" />';
                } else {
                    if ($height >= 500) {
                        $image_height = 400;
                        $image_width = ($image_height * $width) / $height;
                        $newImage = '<img src="' . $imageBase64 . '" id="imageDraw' . $i . '"  style="width:' .  $image_width . 'px;"/>';
                    } else {
                        $image_height = $height;
                        $newImage = '<img src="' . $imageBase64 . '" id="imageDraw' . $i . '" />';
                    }
                }
                $html .= $newImage;
                $evenarrayLeft = [];
                $evenarrayTop = [];
                $sameLocationevenarray = [];
                $sameLocationoddarray = [];

                $oddarrayLeft = [];
                $oddarrayTop = [];



                foreach ($chunk as $key => $value) {
                    $top = $value['position_top'];
                    $left = $value['position_left'];

                    $explode = explode("#", $value['name']);
                    $tooltipText = ($value['type'] == 'sample' ? 's' : 'v') . $explode[1] . "<br/>";
                    if (@$value['check_hazmats']) {
                        $hazmatCount = count($value['check_hazmats']); // Get the total number of elements
                        foreach ($value['check_hazmats'] as $index => $hazmet) {
                            $tooltipText .= '<span style="font-size:8px;color:' . $hazmet['hazmat']['color']   . '">' . $hazmet['hazmat']['short_name'] . '</span>';
                            if ($index < $hazmatCount - 1) {
                                $tooltipText .= ',';
                            }
                        }
                    }
                    $k++;
                    if ($width > 1000 || $height >= 600) {
                        $topshow = ($image_width * $top) / $width;
                        $leftshow = ($image_width * $left) / $width;
                    } else {

                        if ($image_height == 400) {
                            $topshow = ($image_width * $top) / $width;
                            $leftshow = ($image_width * $left) / $width;
                        } else {
                            $topshow = $top;
                            $leftshow = $left;
                        }
                    }
                    $lineLeftPosition =  ($leftshow + 4);

                    if ($k % 2 == 1) {
                        $lineTopPosition = "-" . $gap;
                        $lineHeight = ($topshow + $gap);
                        $tooltipStart = $lineTopPosition - $oddincreaseGap;
                        $oddsameLocation = 0;
                        foreach ($oddarrayLeft as $key => $oddvalue) {
                            if (abs($lineLeftPosition - $oddvalue) < 100 && abs($topshow - $oddarrayTop[$key]) < 100) {
                                $oddsameLocation++;
                                $tooltipStart = $tooltipStart - $oddincreaseGap;
                                $lineHeight = $lineHeight + $oddincreaseGap;
                                $lineTopPosition = $lineTopPosition - $oddincreaseGap;
                            }else{
                                //for else odd i mean line in same place
                                $tooltipStart = $tooltipStart - 29;
                                $lineHeight =  $topshow +  abs($tooltipStart);
                                $lineTopPosition = $tooltipStart;
                            }
                        }
                        if ($oddsameLocation > 1) {
                            foreach ($sameLocationoddarray as $sameLocationoddValue) {
                                if ($sameLocationoddValue == $tooltipStart) {
                                    $tooltipStart = $tooltipStart - 29;
                                    $lineHeight =  $topshow +  abs($tooltipStart);
                                    $lineTopPosition = $tooltipStart;
                                }
                            }
                            $sameLocationoddarray[] = $tooltipStart;
                        }
                        $oddarrayLeft[$value['id']] =  $lineLeftPosition;
                        $oddarrayTop[$value['id']] =  $topshow;
                    } else {
                        $lineTopPosition =   $topshow;
                        $lineHeight = ($image_height - $topshow + $gap);
                        $tooltipStart = $image_height + $gap;
                        $sameLocation = 0;
                        foreach ($evenarrayLeft as $key => $evenvalue) {
                            if (abs($lineLeftPosition - $evenvalue) < 100 || abs($topshow - $evenarrayTop[$key]) < 100) {
                                $sameLocation++;
                                    $tooltipStart = $tooltipStart +  $evenincreaseGap;
                                    $lineHeight = $lineHeight +  $evenincreaseGap;
                            }
                             if(abs($topshow - $evenarrayTop[$key]) < 100)
                             {
                                $tooltipText = "Y".abs($topshow - $evenarrayTop[$key]);
                                $sameLocation++;
                                $tooltipStart = $tooltipStart +  $k;
                                $lineHeight = $lineHeight +  $k;
                             }
                        }
                        if ($sameLocation > 1) {
                            foreach ($sameLocationevenarray as $sameLocationValue) {
                                if ($sameLocationValue == $tooltipStart) {
                                    $tooltipStart = $tooltipStart +  $evenincreaseGap;
                                    $lineHeight = $lineHeight +  $evenincreaseGap;
                                }
                            }
                            $sameLocationevenarray[] = $tooltipStart;
                        }
                       
                       
                        $evenarrayLeft[$value['id']] = $lineLeftPosition;
                        $evenarrayTop[$value['id']] =  $topshow;

                        
                    }
                    $html .= '<div class="dot" style="top:' . $topshow . 'px; left:' . $leftshow . 'px; position: absolute;border: 4px solid #4052d6;background: #4052d6;border-radius: 50%;"></div>';

                    $html .= '<span class="line" style="top:' . $lineTopPosition  . 'px;left:' . $lineLeftPosition . 'px;height:' . $lineHeight . 'px;' . $lineCss . '"></span>';


                    $html .= '<span class="tooltip" style="' . $tooltipCss . 'top:' . $tooltipStart . 'px; left:' . ($lineLeftPosition - 15) . 'px">' . $tooltipText . '</span>';
                }
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';

                $i++; // Increment the counter for the next image ID

            }
        }


        return $html;
    }
    protected function mergeImageToPdf($imagePath, $title, $mpdf, $page = null)
    {
        $mpdf->AddPage($page);
        $mpdf->WriteHTML('<h1>' . $title . '</h1>');
        $mpdf->Image($imagePath, 0, 20,  $mpdf->w, null, 'png', '', true, false);
    }
    protected function mergePdf($filePath, $title, $mpdf, $page = null)
    {
        $mpdf->setSourceFile($filePath);
        $pageCount = $mpdf->setSourceFile($filePath);
        for ($i = 1; $i <= $pageCount; $i++) {

            $mpdf->AddPage($page);
            $templateId = $mpdf->importPage($i);
            $size = $mpdf->getTemplateSize($templateId);
            $scale = min(
                ($mpdf->w - $mpdf->lMargin - $mpdf->rMargin) / $size['width'],
                ($mpdf->h - $mpdf->tMargin - $mpdf->bMargin) / $size['height']
            );
            if ($i === 1 && @$title) {
                $mpdf->WriteHTML($title);
                $lmargin = 10;
                $tMargin = 20;
            } else {
                $lmargin = $mpdf->lMargin;
                $tMargin = $mpdf->tMargin;
            }
            $mpdf->useTemplate($templateId, $lmargin, $tMargin, $size['width'] * $scale, $size['height'] * $scale);
        }
    }

    public function demoTest(){
        $ChecksList = CheckHasHazmat::get();
        foreach($ChecksList as $value){
             $check_id = $value['check_id'];
             $hazmat_id = $value['hazmat_id'];
            $lebResurt = LabResult::where('check_id',$check_id)->where('hazmat_id',$hazmat_id)->first();
            if($lebResurt){
                $type = $lebResurt['type'];
            }else{
                $type = 'Not Contained';
            }
           CheckHasHazmat::where('id',$value['id'])->update(['final_lab_result'=>$type]);
        }
    }
}

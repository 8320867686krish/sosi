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
        $imo_number = $project["imo_number"];

        if ($isSample) {
            $checks = $checks->where('type', 'sample');
            $filename = "Lab-Test-List-{$ship_name}" . "." . $fileExt;
        } else {
            $filename = "VSCP-{$ship_name}-{$imo_number}" . "." . $fileExt;
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
            $query->whereHas('check_hazmats', function ($query) {
                $query->where('type', 'PCHM')->orWhere('type', 'Contained');
            });
        }])->where('project_id', $project_id)->get();
        $filename = $this->drawDigarm($decks);

        try {
            // Create an instance of mPDF with specified margins
            $mpdf = new Mpdf([
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 16,
                'margin_footer' => 13,
            ]);
            $mpdf->defaultPageNumStyle = '1';
            $mpdf->SetDisplayMode('fullpage');
            $pageCount = $mpdf->setSourceFile(storage_path('app/pdf/') . "/" . $filename);
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
                    <td width="33%" style="text-align: left;">' . $projectDetail['ihm_table'] . '</td>
                    <td width="33%" style="text-align: center;">Revision:' . $version . '</td>
                    <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
                </tr>
            </table>';
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);

            $stylesheet = file_get_contents('public/assets/mpdf.css');
            $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

            $mpdf->WriteHTML(view('report.cover', compact('projectDetail')));
            $mpdf->WriteHTML(view('report.shipParticular', compact('projectDetail')));
            $mpdf->AddPage('L'); // Set landscape mode for the inventory page

            $mpdf->WriteHTML(view('report.Inventory', compact('filteredResults1', 'filteredResults2', 'filteredResults3', 'decks')));
            for ($i = 1; $i <= $pageCount; $i++) {
                $mpdf->AddPage('L');
                if ($i = 1) {
                    $mpdf->writeHtml('<h3>Location Diagram</h3>');
                }
                $templateId = $mpdf->importPage($i);
                $mpdf->useTemplate($templateId, 50, 20, null, null);
            }
            $mpdf->Output();
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
        if ($request->input('action') == "summery") {

            return $this->summeryReport($post);
        }

        $projectDetail = Projects::with('client')->find($project_id);
        if (!$projectDetail) {
            die('Project details not found');
        }
        $reportType = $projectDetail['ihm_table'];
        $imageData = file_get_contents($projectDetail['image']);
        $report_materials = ReportMaterial::where('project_id', $project_id)->get()->toArray();
        $foundItems = [];

        foreach ($report_materials as $value) {
            $index = array_search($value['structure'], array_column($report_materials, 'structure'));
            if ($index !== false) {
                $foundItems[$value['structure']] = $report_materials[$index];
            }
        }

        $html = '';
        $decks = Deck::with(['checks' => function ($query) {
            $query->whereHas('check_hazmats', function ($query) {
                $query->where('type', 'PCHM')->orWhere('type', 'Contained');
            });
        }])->where('project_id', $project_id)->get();

        if ($reportType == 'IHM Part 1' || $reportType == 'IHM Gap Analysis') {
            $filename = $this->drawDigarm($decks);
        }

        $logo = 'https://sosindi.com/IHM/public/assets/images/logo.png';
        $hazmets = Hazmat::withCount(['checkHasHazmats as check_type_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->withCount(['checkHasHazmatsSample as sample_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->withCount(['checkHasHazmatsVisual as visual_count' => function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        }])->get();

        $ChecksList = Deck::with(['checks.check_hazmats'])->where('project_id', $project_id)->get();
        $ChecksListImage = Checks::with(['checkSingleimage', 'labResults'])->where('project_id', $project_id)->get();
        $sampleImage = $ChecksListImage->filter(function ($item) {
            return $item->type == 'sample';
        });

        $visualImage = $ChecksListImage->filter(function ($item) {
            return $item->type == 'visual';
        });
        $lebResultAll = LabResult::with(['check', 'hazmat'])->where('project_id', $project_id)->where('type', 'Contained')->orwhere('type', 'PCHM')->get();

        $attechments = Attechments::where('project_id', $project_id)->where('attachment_type', '!=', 'shipBrifPlan')->get();
        $brifPlan = Attechments::where('project_id', $project_id)->where('attachment_type', '=', 'shipBrifPlan')->first();

        if (@$brifPlan['documents']) {
            $brifimage = public_path('images/attachment') . "/" . $projectDetail['id'] . "/" . $brifPlan['documents'];
        } else {
            $brifimage = '';
        };
        $attechmentsResult = $attechments->filter(function ($item) {
            return $item->attachment_type == 'shipPlan';
        });
        $check_has_hazmats = CheckHasHazmat::where('project_id', $project_id)->get();
        $filteredResults1 = $lebResultAll->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-1';
        });

        $filteredResults2 = $lebResultAll->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-2';
        });
        $filteredResults3 = $lebResultAll->filter(function ($item) {
            return $item->IHM_part == 'IHMPart1-3';
        });
        try {
            // Create an instance of mPDF with specified margins
            $mpdf = new Mpdf([
                'mode' => 'c',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 27,
                'margin_bottom' => 25,
                'margin_header' => 16,
                'margin_footer' => 13,
                'defaultPagebreakType' => 'avoid',
                'imageProcessor' => 'GD', // or 'imagick' if you have Imagick installed
                'jpeg_quality' => 75, // Set the JPEG quality (0-100)
                'shrink_tables_to_fit' => 1, // Shrink tables to fit the page width
                'tempDir' => __DIR__ . '/tmp', // Set a temporary directory for mPDF


                'allow_output_buffering' => true,
            ]);

            $mpdf->defaultPageNumStyle = '1';
            $mpdf->SetDisplayMode('fullpage');

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
            if ($reportType == 'IHM Part 1') {
                $mpdf->WriteHTML(view('report.introduction', compact('hazmets', 'projectDetail')));
            }else{
                    $mpdf->WriteHTML(view('report.gapAnaylisis', compact('projectDetail', 'hazmets')));

            }
                $mpdf->AddPage('L'); // Set landscape mode for the inventory page
                $pageCount = $mpdf->setSourceFile(storage_path('app/pdf/') . "/" . $filename);

                $mpdf->WriteHTML(view('report.Inventory', compact('filteredResults1', 'filteredResults2', 'filteredResults3')));

                for ($i = 1; $i <= $pageCount; $i++) {
                    $mpdf->AddPage('L');
                    if ($i = 1) {
                        $mpdf->writeHtml('<h3 style="font_size:14px;">Location Diagram</h3>');
                    }
                    $templateId = $mpdf->importPage($i);
                    $mpdf->useTemplate($templateId, 50, 20, null, null);
                    // Get the dimensions of the current page

                }
                unlink(storage_path('app/pdf/') . "/" . $filename);

                $mpdf->AddPage('p');
                $mpdf->WriteHTML(view('report.development', compact('projectDetail', 'attechmentsResult', 'ChecksList', 'foundItems')));
                $mpdf->writeHtml('<h3 style="font_size:14px;">3.4 VSCP Preparation</h3>');
                foreach ($ChecksList as $value) {
                    if (count($value['checks']) > 0) {
                        $pageWidth = $mpdf->w;

                        $vspPrepration = $this->drawDigarmWithTable($value,$pageWidth);

                        $vspPreprationpageCount = $mpdf->setSourceFile(storage_path('app/pdf/') . "/" . $vspPrepration);

                        for ($i = 1; $i <= $vspPreprationpageCount; $i++) {
                            $mpdf->AddPage('L');
                            if ($i = 1) {
                                $mpdf->writeHtml('<h4 style="font_size:12px;text-align:center">' . $value['name'] . '</h4>');
                            }
                            $templateId = $mpdf->importPage($i);
                            $mpdf->useTemplate($templateId, 0, 20, null, null);
                            // Get the dimensions of the current page

                        }
                      //  unlink(storage_path('app/pdf/') . "/" . $vspPrepration);
                        $render = view('report.vscpPrepration', ['checks' => $value['checks']])->render();
                        $mpdf->AddPage('p');
                        $mpdf->writeHtml($render);
                    }
                }
                $mpdf->AddPage('p');
                $mpdf->WriteHTML(view('report.IHM-VSC', compact('projectDetail', 'brifimage', 'lebResultAll')));
                $mpdf->AddPage('L');
                $mpdf->WriteHTML(view('report.VisualSamplingCheck', compact('ChecksList')));

                $mpdf->AddPage('L');
                $mpdf->WriteHTML(view('report.riskAssessments'));
                $mpdf->WriteHTML(view('report.sampleImage', compact('sampleImage', 'visualImage')));
                $titleattach = '<h2 style="text-align:center">Appendix-4 Supporting Documents/plans from Ship</h2>';

                $i = 0;
                foreach ($check_has_hazmats as $checkvalue) {
                    $image = $checkvalue->image;

                    if (@$image) {

                        $imageValue = basename($checkvalue->image);
                        if (@$imageValue) {
                            $i++;
                            $filePath = public_path('images/hazmat') . "/" . $projectDetail['id'] . "/" . $imageValue;

                            if (file_exists($filePath)) {
                                if ($i == 1) {
                                    $this->mergePdf($filePath, $titleattach, $mpdf);
                                } else {
                                    $this->mergePdf($filePath, null, $mpdf);
                                }
                            }
                        }
                    }

                    $imageDoc = $checkvalue->doc;

                    if (@$imageDoc) {
                        $imageDocValue = basename($checkvalue->imadocge);
                        if (@$imageDocValue) {
                            $filePathDoc = public_path('images/hazmat') . "/" . $projectDetail['id'] . "/" . $imageDocValue;
                            if (file_exists($filePathDoc)) {
                                $this->mergePdf($filePathDoc, null, $mpdf);
                            }
                        }
                    }
                }
                $attachmentCount = 0;

                if (@$projectDetail['leb1LaboratoryResult1']) {


                    $filePath = public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb1LaboratoryResult1'];
                    if (file_exists($filePath) && @$projectDetail['leb1LaboratoryResult1']) {
                        $attachmentCount++;
                        $titleHtml = '<h2 style="text-align:center;font_size:12px;" id="lebResult">Attachment ' . $attachmentCount . ' Leb Result1 for leb1</h2>';

                        $this->mergePdf($filePath, $titleHtml, $mpdf);
                    }
                }
                if (@$projectDetail['leb1LaboratoryResult2']) {

                    $filePath1 = public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb1LaboratoryResult2'];
                    if (file_exists($filePath1) && @$projectDetail['leb1LaboratoryResult2']) {
                        $attachmentCount++;
                        $titleHtml = '<h2 style="text-align:center;font_size:12px;padding-bottom:10px;" id="lebResult">Attachment ' . $attachmentCount . ' Leb Result1 for leb2</h2>';
                        $this->mergePdf($filePath1, $titleHtml, $mpdf);
                    }
                }

                if (@$projectDetail['leb2LaboratoryResult1']) {

                    $filePath2 = public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb2LaboratoryResult1'];
                    if (file_exists($filePath2) && @$projectDetail['leb2LaboratoryResult1']) {
                        $attachmentCount++;
                        $titleHtml = '<h2 style="text-align:center;font_size:12px;padding-bottom:10px;" id="lebResult">Attachment ' . $attachmentCount . ' Leb Result1 for leb2</h2>';
                        $this->mergePdf($filePath2, $titleHtml, $mpdf);
                    }
                }

                if (@$projectDetail['leb2LaboratoryResult2']) {

                    $filePath3 = public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb2LaboratoryResult2'];
                    if (file_exists($filePath) && @$projectDetail['leb2LaboratoryResult2']) {
                        $attachmentCount++;
                        $titleHtml = '<h2 style="text-align:center;font_size:12px;" id="lebResult">Attachment ' . $attachmentCount . ' Leb Result2 for leb2</h2>';
                        $this->mergePdf($filePath3, $titleHtml, $mpdf);
                    }
                }

                $gaPlan = public_path('images/projects') . "/" . $projectDetail['id'] . "/" . $projectDetail['deck_image'];
                if (file_exists($gaPlan) && @$projectDetail['ga_plan']) {
                    $attachmentCount++;
                    $titleattach = '<h2 style="text-align:center;font-size:12px;">AttachMent ' . $attachmentCount . ' Ga Plan </h2>';
                    // $gaplan = 'https://sosindi.com/IHM/public/images/projects/12/amanah_1716551673.png';
                    $this->mergeImageToPdf($gaPlan, $titleattach, $mpdf, $page = 'L');
                }
                foreach ($attechments as $value) {

                    $titleattach = '<h2 style="text-align:center;font-size:12px;">Attachment ' . $attachmentCount . " " . $value['heading'] . '</h2>';

                    $filePath = public_path('images/attachment') . "/" . $projectDetail['id'] . "/" . $value['documents'];
                    if (file_exists($filePath) && @$value['documents']) {
                        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        $attachmentCount++;
                        if ($fileExtension === 'pdf') {
                            // If it's a PDF, merge it
                            $this->mergePdf($filePath, $titleattach, $mpdf);
                        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            // If it's an image, convert to PDF and merge
                            $this->mergeImageToPdf($filePath, $titleattach, $mpdf);
                        }
                    }
                }
            
          

            $mpdf->Output('project_report.pdf', 'I');
        } catch (\Mpdf\MpdfException $e) {
            // Handle mPDF exception
            echo $e->getMessage();
        }
    }
    public function drawDigarm($decks)
    {
        $options = new Options();
        $dompdf = new Dompdf($options);
        $html = "";
        $html .= "<div style='text-align: center;'>";
        $html .= "<div class='maincontnt' style='display: flex;justify-content: center;align-items: center;
        flex-direction: column;'>";
        foreach ($decks as $deck) {
            // Convert the image to base64
            $imagePath = $deck['image'];
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
            list($imageWidth, $imageHeight) = getimagesize($imagePath);

            if (count($deck['checks']) > 0) {
                // Background image using base64
                $html .= '<span class="image-container" style="  position: relative;
                display: inline-block;
                margin: 20px;">';
                $html .= '<img src="' . $imageBase64 . '"  />';
                if (!empty($deck['checks'])) {
                    foreach ($deck['checks'] as $key => $value) {
                        $top = $value->position_top;
                        $left = $value->position_left;
                        $html .= '<span class="dot" style="top:' . $top . 'px;left:' . $left . 'px; position: absolute;
                        width: 12px;
                        height: 12px;
                        border: 2px solid #BF0A30;
                        background: #BF0A30;
                        border-radius: 50%;
                        text-align: center;
                        line-height: 20px;"></span>';
                        $addInLeft = "right";
                        if (($imageWidth / 2) > $left) {
                            $addInLeft = "left";
                        }
                        $html .= '<span class="tooltip" style="position: absolute;
                        background-color: #fff;
                        border: 1px solid #BF0A30;
                        padding-left: 2px;
                        padding-right: 2px;
                        border-radius: 5px;
                        white-space: nowrap;
                        z-index: 1; /* Ensure tooltip is above the dots and lines */
                        color:#BF0A30;';

                        // Calculate tooltip position
                        $toolTipTop = $top;
                        $startPosition = $left;
                        $addInLeft = $addInLeft;
                        $tooltipText = $value['name'] . '<br/>' . $value['type'];
                        $tooltipWidth = strlen($tooltipText) * 4;
                        $leftP = $tooltipWidth + 20;
                        $rightPosition = "-" . $leftP . "px";
                        if ($addInLeft == "right") {
                            // Adjust tooltip position to the right

                            $html .= 'top: ' . ($toolTipTop - 5) . 'px; right:' . $rightPosition;
                            $totalWidthLine = $imageWidth - $startPosition + $leftP;
                        } else {
                            $html .= 'top: ' . ($toolTipTop - 5) . 'px; left:' . $rightPosition;
                            $totalWidthLine = $left + $leftP;
                        }
                        $totalWidthLineRound = abs($totalWidthLine) . 'px';
                        $linetop = ($toolTipTop + 7) . 'px';

                        $html .= '">' . $tooltipText . '</span>';
                        $tooltipWidth = strlen($tooltipText); // Estimate width based on character count

                        if ($addInLeft == "right") {
                            $html .= '<span class="line" style="position: absolute;
                        width: 1px;
                        background-color: #BF0A30;top:' . $linetop . ';right:' . $rightPosition . ';height:2px;width:' . $totalWidthLineRound . '"></span>';
                        } else {
                            $html .= '<span class="line" style="position: absolute;
                            width: 1px;
                            background-color: #BF0A30;top:' . $linetop . ';left:' . $rightPosition . ';height:2px;width:' . $totalWidthLineRound . '"></span>';
                        }
                    }
                }
                $html .= '</span>';
            }
        }
        $html .= '</div>';
        $html .= '</div>';

        $dompdf->loadHtml($html);

        $dompdf->render();
        $coverPdfContent = $dompdf->output();
        $filename = "project" . uniqid() . ".pdf";
        $filePath = storage_path('app/pdf/') . "/" . $filename;
        file_put_contents($filePath, $coverPdfContent);
        return $filename;
    }
    public function drawDigarmwithTable($decks,$pageWidth)
    {
        $options = new Options();
        $dompdf = new Dompdf($options);
        $html = "";
        $html .= "<div style='text-align: center;'>";
        $html .= "<div class='maincontnt' style='display: flex;justify-content: center;align-items: center;
        flex-direction: column;'>";
        // Convert the image to base64
        $imagePath = $decks['image'];
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
       
        list($imageWidth, $imageHeight) = getimagesize($imagePath);
       

        if (count($decks['checks']) > 0) {
            // Background image using base64

            $html .= '<div class="image-container" style="  position: relative;
                display: inline-block;
                margin: 20px;">';
                $html .= '<img src="' . $imageBase64 . '"/>';
                if (!empty($decks['checks'])) {
                $i=0;
                foreach ($decks['checks'] as $key => $value) {
                    $i++;
                    $hazmatsCount = count($value->check_hazmats);
                    $top = $value->position_top;
                    $left = $value->position_left;

                    $html .= '<div class="dot" style="top:' . $top . 'px;left:' . $left . 'px; position: absolute;
                            width: 12px;
                            height: 12px;
                            border: 2px solid #BF0A30;
                            background: #BF0A30;
                            border-radius: 50%;
                            text-align: center;
                            line-height: 20px;"></div>';
                    $addInLeft = "right";
                    if (($imageWidth / 2) > $left) {
                        $addInLeft = "left";
                    }
                    $html .= '<div class="tooltip" style="position: absolute;
                        background-color: #fff;
                        border: 1px solid #BF0A30;
                        padding: 3px;
                        border-radius: 5px;
                        white-space: nowrap;
                        z-index: 1; /* Ensure tooltip is above the dots and lines */
                        color:#BF0A30;font-size:10px;';

                    // Calculate tooltip position
                    $toolTipTop = $top;
                    $startPosition = $left;
                    $addInLeft = $addInLeft;
                    $extract = explode("#", $value['name']);
                    if ($hazmatsCount == 0) {
                        $tooltipText = ($value['type'] == 'sample' ? 'S' : 'V') . $extract[1];
                    } else {
                        $tooltipText = ($value['type'] == 'sample' ? 'S' : 'V') . $extract[1];
                    }

                    $tooltipWidth = strlen($tooltipText) * 4;
                    $leftP = ($i % 2 == 0) ? $tooltipWidth * 2 : $tooltipWidth  * $i;
                    $rightPosition = "-" . $leftP . "px";
                    if ($addInLeft == "right") {
                        // Adjust tooltip position to the right

                        $html .= 'top: ' . ($toolTipTop - 5) . 'px; right:' . $rightPosition;
                        $totalWidthLine = $imageWidth - $startPosition + $leftP;
                    } else {
                        $html .= 'top: ' . ($toolTipTop - 5) . 'px; left:' . $rightPosition;
                        $totalWidthLine = $left + $leftP;
                    }
                    $totalWidthLineRound = abs($totalWidthLine) . 'px';
                    $linetop = ($toolTipTop + 7) . 'px';

                    $html .= '">' . $tooltipText . "<br/>";
                    if ($hazmatsCount != 0) {
                        foreach ($value->check_hazmats as $index => $hazmat) {
                            $html .= '<span class="subcircle" style="color:' . $hazmat["hazmat"]["color"] . ';">.' . $hazmat["hazmat"]["short_name"] . '</span>';
                        }
                    }

                    $html .= '</div>';
                    $tooltipWidth = strlen($tooltipText); // Estimate width based on character count

                    if ($addInLeft == "right") {
                        $html .= '<span class="line" style="position: absolute;
                        width: 1px;
                        background-color: #BF0A30;top:' . $linetop . ';right:' . $rightPosition . ';height:2px;width:' . $totalWidthLineRound . '"></span>';
                    } else {
                        $html .= '<span class="line" style="position: absolute;
                            width: 1px;
                            background-color: #BF0A30;top:' . $linetop . ';left:' . $rightPosition . ';height:2px;width:' . $totalWidthLineRound . '"></span>';
                    }
                }
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';
        $dompdf->loadHtml($html);
        $dompdf->render();
        $coverPdfContent = $dompdf->output();
        $filename = "project" . uniqid() . "ab.pdf";
        $filePath = storage_path('app/pdf/') . "/" . $filename;
        file_put_contents($filePath, $coverPdfContent);
        return $filename;
    }
    protected function mergeImageToPdf($imagePath, $title, $mpdf, $page = null)
    {
        list($width, $height) = getimagesize($imagePath);

        $pageWidth = $mpdf->w; // Width of the page in mm
        $pageHeight = $mpdf->h; // Height of the page in mm

        // Calculate the x position to center the image
        $imageX = ($pageWidth - $width) / 2;
        $imageY = 35; // Adjust the Y position as needed
        $mpdf->AddPage($page);

        // Add the title
        $mpdf->WriteHTML('<h1>' . $title . '</h1>');

        // Add the image to the page
        $mpdf->Image($imagePath, 0, 35,  $mpdf->w, null, 'png', '', true, false);
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
                $lmargin = 25;
            } else {
                $lmargin = $mpdf->lMargin;
            }
            // Use the template and apply the calculated scale
            $mpdf->useTemplate($templateId, $lmargin, $mpdf->tMargin, $size['width'] * $scale, $size['height'] * $scale);
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

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
use App\Models\CheckHasHazmat;
use App\Models\Deck;
use App\Models\ReportMaterial;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

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
        $html = '';
        $html .= "<h3>Location Diagram</h3>";
        foreach ($decks as $deck) {
            // Convert the image to base64
            $imagePath = $deck['image'];
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;

            // Main container
            $html .= '<div style="position: relative;>';
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
                        $html .= '<div style="position: absolute; top: ' . $top . 'px; left: ' . $left . 'px; width: 20px; height: 20px; border: 2px solid red; background: red;border-radius:50%;  text-align: center; line-height: 5mm;"  class="parentDot" >';
                        $html .= '<div class="tooltip" style="display: block; position: relative; top: -10px; left: 183px; background-color: #fff; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">' . $value['name'] . '</div>';

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
                $templateId = $mpdf->importPage($i);
                $mpdf->useTemplate($templateId, 0, 0, 100, 100);
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
        $reportType =  $projectDetail['ihm_table'];
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
        if ($reportType == 'IHM Part 1') {
          $filename= $this->drawDigarm($decks);
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

        $lebResult = LabResult::with(['check', 'hazmat'])->where('project_id', $project_id)->where('type', 'Contained')->orwhere('type', 'PCHM')->get();
        $lebResultAll = LabResult::with(['check.checkSingleimage', 'hazmat'])->where('project_id', $project_id)->get();
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
            $pageCount = $mpdf->setSourceFile(storage_path('app/pdf/')."/".$filename);

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
                'toc-resetpagenum' => 1
            ]);
            if ($reportType == 'IHM Part 1') {
                $mpdf->WriteHTML(view('report.introduction', compact('hazmets', 'projectDetail')));
                $mpdf->AddPage('L'); // Set landscape mode for the inventory page

                $mpdf->WriteHTML(view('report.Inventory', compact('filteredResults1', 'filteredResults2', 'filteredResults3')));

                for ($i = 1; $i <= $pageCount; $i++) {
                    $mpdf->AddPage('L');
                    if($i = 1){
                        $mpdf->writeHtml('<h3>Location Diagram</h3>');
                    }
                    $templateId = $mpdf->importPage($i);
                    $mpdf->useTemplate($templateId,50,20,null,null);
                    // Get the dimensions of the current page
                
                }
                $mpdf->AddPage('p');
                $mpdf->WriteHTML(view('report.development', compact('projectDetail', 'attechmentsResult', 'ChecksList', 'foundItems')));
                $mpdf->WriteHTML(view('report.IHM-VSC', compact('projectDetail', 'brifimage', 'lebResultAll', 'ChecksList')));
                $mpdf->AddPage('L');
                $mpdf->WriteHTML(view('report.VisualSamplingCheck', compact('ChecksList')));

                $mpdf->AddPage('L');
                $mpdf->WriteHTML(view('report.riskAssessments'));
                $mpdf->WriteHTML(view('report.sampleImage', compact('lebResultAll')));


                $titleHtml = '<h2 style="text-align:center" id="lebResult">Appendix-4 Leb Result</h2>';

                $filePath =  public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb1LaboratoryResult1'];
                if (file_exists($filePath) && @$projectDetail['leb1LaboratoryResult1']) {
                    $this->mergePdf($filePath, $titleHtml, $mpdf);
                }
                $filePath1 =  public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb1LaboratoryResult2'];
                if (file_exists($filePath1) && @$projectDetail['leb1LaboratoryResult2']) {
                    $this->mergePdf($filePath1, null, $mpdf);
                }
                $filePath2 =  public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb2LaboratoryResult1'];
                if (file_exists($filePath2) && @$projectDetail['leb2LaboratoryResult1']) {
                    $this->mergePdf($filePath2, null, $mpdf);
                }
                $filePath3 =  public_path('images/labResult') . "/" . $projectDetail['id'] . "/" . $projectDetail['leb2LaboratoryResult2'];
                if (file_exists($filePath) && @$projectDetail['leb2LaboratoryResult2']) {
                    $this->mergePdf($filePath3, null, $mpdf);
                }
                $titleattach = '<h2 style="text-align:center">Appendix-5 Supporting Documents/plans from Ship</h2>';

                $i = 0;
                foreach ($check_has_hazmats as $checkvalue) {
                    $image = $checkvalue->image;

                    if (@$image) {
                        $i++;
                        $imageValue = basename($checkvalue->image);
                        $filePath =  public_path('images/hazmat') . "/" . $projectDetail['id'] . "/" . $imageValue;

                        if (file_exists($filePath)) {
                            if ($i == 1) {
                                $this->mergePdf($filePath, $titleattach, $mpdf);
                            } else {
                                $this->mergePdf($filePath, null, $mpdf);
                            }
                        }
                    }

                    $imageDoc = $checkvalue->doc;

                    if (@$imageDoc) {
                        $imageDocValue = basename($checkvalue->imadocge);
                        $filePathDoc =  public_path('images/hazmat') . "/" . $projectDetail['id'] . "/" . $imageDocValue;
                        if (file_exists($filePathDoc)) {
                            $this->mergePdf($filePath, null, $mpdf);
                        }
                    }
                }
                $gaPlan =  public_path('images/projects') . "/" . $projectDetail['id'] . "/" . $projectDetail['deck_image'];
                $attachmentCount = 1;
                if (file_exists($gaPlan) && @$projectDetail['ga_plan']) {
                    $titleattach = '<h2 style="text-align:center">AttachMent ' . $attachmentCount . ' Ga Plan </h2>';

                    $this->mergeImageToPdf($gaPlan, $titleattach, $mpdf, $page = 'L');
                }
                foreach ($attechments as $value) {
                    $attachmentCount++;
                    $titleattach = '<h2 style="text-align:center">AttachMent ' . $attachmentCount . " " . $value['heading'] . '</h2>';

                    $filePath =  public_path('images/attachment') . "/" . $projectDetail['id'] . "/" . $value['documents'];
                    if (file_exists($filePath) && @$value['documents']) {
                        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                        if ($fileExtension === 'pdf') {
                            // If it's a PDF, merge it
                            $this->mergePdf($filePath,  $titleattach, $mpdf);
                        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            // If it's an image, convert to PDF and merge
                            $this->mergeImageToPdf($filePath, $titleattach, $mpdf);
                        }
                    }
                }
            } else if ($reportType == 'IHM Gap Analysis') {
                $mpdf->WriteHTML(view('report.gapAnaylisis', compact('projectDetail', 'hazmets')));
                $mpdf->WriteHTML(view('report.Inventory', compact('filteredResults1', 'filteredResults2', 'filteredResults3')));
            }


            $mpdf->Output('project_report.pdf', 'I');
        } catch (\Mpdf\MpdfException $e) {
            // Handle mPDF exception
            echo $e->getMessage();
        }
    }
    public function drawDigarm($decks){
        $options = new Options();
        $dompdf = new Dompdf($options);
        $html="";
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
                        border: 2px solid #FF0000;
                        background: #FF0000;
                        border-radius: 50%;
                        text-align: center;
                        line-height: 20px;"></span>';
                        $addInLeft = "right";
                        if (($imageWidth / 2) > $left) {
                            $addInLeft = "left";
                        }
                        $html .= '<span class="tooltip" style="position: absolute;
                        background-color: #fff;
                        border: 1px solid #FF0000;
                        padding: 15px;
                        border-radius: 5px;
                        white-space: nowrap;
                        z-index: 1; /* Ensure tooltip is above the dots and lines */
                        color:#FF0000;';

                        // Calculate tooltip position
                        $toolTipTop = $top;
                        $startPosition = $left;
                        $addInLeft = $addInLeft;

                        if ($addInLeft == "right") {
                            // Adjust tooltip position to the right
                            $html .= 'top: ' . ($toolTipTop - 5) . 'px; right: -50px;';
                            $totalWidthLine = $imageWidth - $startPosition + 50;
                        } else {
                            $html .= 'top: ' . ($toolTipTop - 5) . 'px; left: -100px;';
                            $totalWidthLine = $left + 100;
                        }
                        $totalWidthLineRound = abs($totalWidthLine) . 'px';
                        $linetop = ($toolTipTop + 7) . 'px';
                        $html .= '">' . $value['name'] .'<br/>'.$value['type']. '</span>';
                        if ($addInLeft == "right") {
                            $html .= '<span class="line" style="position: absolute;
                        width: 1px;
                        background-color: #FF0000;top:' . $linetop . ';right:-50px;height:2px;width:' . $totalWidthLineRound . '"></span>';
                        } else {
                            $html .= '<span class="line" style="position: absolute;
                            width: 1px;
                            background-color: #FF0000;top:' . $linetop . ';left:-100px;height:2px;width:' . $totalWidthLineRound . '"></span>';
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
        $filename = "project".uniqid().".pdf";
        $filePath = storage_path('app/pdf/')."/".$filename ;
        file_put_contents($filePath, $coverPdfContent);
        return $filename;
    }
    protected function mergeImageToPdf($imagePath, $title, $mpdf, $page = null)
    {
        list($width, $height) = getimagesize($imagePath);

        // Define page dimensions based on the given page format
        $pageWidth = $mpdf->w - $mpdf->lMargin - $mpdf->rMargin; // Considering margins
        $pageHeight = $mpdf->h - $mpdf->tMargin - $mpdf->bMargin; // Considering margins

        // Calculate the aspect ratio
        $imageAspect = $width / $height;
        $pageAspect = $pageWidth / $pageHeight;

        // Scale image dimensions to fit within page dimensions
        if ($imageAspect > $pageAspect) {
            // Scale image to fit page width
            $newWidth = $pageWidth;
            $newHeight = $pageWidth / $imageAspect;
        } else {
            // Scale image to fit page height
            $newHeight = $pageHeight;
            $newWidth = $pageHeight * $imageAspect;
        }

        // Center the image on the page
        $x = ($pageWidth - $newWidth) / 2 + $mpdf->lMargin;
        $y = ($pageHeight - $newHeight) / 2 + $mpdf->tMargin;

        // Create a new PDF page
        $mpdf->AddPage($page);

        // Add the title
        $mpdf->WriteHTML('<h1>' . $title . '</h1>');

        // Add the image to the page
        $mpdf->Image($imagePath, $x, 35, 0, $newHeight, 'png', '', true, false);
    }
    protected function mergePdf($filePath, $title, $mpdf, $page = null)
    {
        $mpdf->setSourceFile($filePath);

        $pageCount = $mpdf->setSourceFile($filePath);
        for ($i = 1; $i <= $pageCount; $i++) {

            $mpdf->AddPage($page);

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

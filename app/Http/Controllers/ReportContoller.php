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
    
    $decksval = Deck::with(['checks'])->where('project_id', $project_id)->get();

    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);

    $html = "";
    $i = 1; // Initialize a counter for the image ID

    foreach ($decksval as $decks) {
        $html .= "<div class='maincontnt' style='display: flex; justify-content: center; align-items: center; flex-direction: column;page-break-after: always; height:100vh;'>";
      //  $html .= '<p>' . htmlspecialchars($decks['name'], ENT_QUOTES, 'UTF-8') . '</p>';
        
        // Convert the image to base64
        $imagePath = $decks['image'];
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;

        list($width, $height) = getimagesize($imagePath);
 $html .= '<div style="margin-top:25%">';
        $html .= '<div class="image-container" id="imgc' . $i . '" style="position: relative; display: inline-block; width:100%;">';
        $image_width  = 1024;
      

        if ($width > 1000) {
            $newImage = '<img src="' . $imageBase64 . '" id="imageDraw' . $i . '" style="width:'.  $image_width .'px" />';
        } else {
            $newImage = '<img src="' . $imageBase64 . '" id="imageDraw' . $i . '" />';
        }

        $html .= $newImage;
        $k = 0;
        foreach ($decks['checks'] as $key => $value) {
            $top = $value->position_top;
            $left = $value->position_left;
               $k++; 
            if ($width > 1000) {
                $topshow = ($image_width * $top) / $width;
                $leftshow = ($image_width * $left) / $width;
 
                      
                $html .= '<div class="dot" style="top:' . $topshow . 'px; left:' . $leftshow . 'px; position: absolute;
                    width: 2px;
                    height: 2px;
                    border: 2px solid #BF0A30;
                    background: #BF0A30;
                    border-radius: 50%;
                    text-align: center;
                    line-height: 20px;"></div>';
                
                // $html .= '<span class="tooltip" style="position: absolute;
                //         background-color: #fff;
                //         border: 1px solid #BF0A30;
                //         padding-left: 2px;
                //         padding-right: 2px;
                //         border-radius: 5px;
                //         white-space: nowrap;
                //         z-index: 1; /* Ensure tooltip is above the dots and lines */
                //         color:#BF0A30;';
                        if($k % 2 == 0){
                               $linetop =  $topshow - 20;
                         $html .= '<span class="line" style="position: absolute;background-color: #BF0A30;top:' . $linetop .';left:'.$leftshow.';height:70px;width:5px;"></span>';
                        }
                  
                        
                            // Adjust tooltip position to the right

                         //   $html .= 'top: ' . ($toolTipTop - 5) . 'px; right:' . $rightPosition;
                        //    $totalWidthLine = $width - $startPosition + $leftP;
                        
                      //  $totalWidthLineRound = abs($totalWidthLine) . 'px';
                      //  $linetop = ($toolTipTop + 7) . 'px';

                      //  $html .= '">' . $tooltipText . '</span>';
                       
                      

            } else {
                $html .= '<div class="dot" style="top:' . $top . 'px; left:' . $left . 'px; position: absolute;
                    width: 2px;
                    height: 2px;
                    border: 2px solid #BF0A30;
                    background: #BF0A30;
                    border-radius: 50%;
                    text-align: center;
                    line-height: 20px;"></div>';
            }

        }
 $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        $i++; // Increment the counter for the next image ID
        if($i == 3){
            echo $html;
            exit();
        }
       
    }
    

    // Debugging step: echo the generated HTML to see the structure and content

    // Load HTML into Dompdf
    $dompdf->loadHtml($html);

    // Set the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

    // Stream the PDF to the browser
    $dompdf->stream('document.pdf', [
        'Attachment' => false // Set to true to download the file
    ]);

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
    public function drawDigarmwithTable($decks, $pageWidth)
    {
        $options = new Options();
        $dompdf = new Dompdf($options);
$dompdf->set_paper("a4", "lendscape");

        $html = "";
        $html .= "<div class='maincontnt' style='display: flex;justify-content: center;align-items: center;
        flex-direction: column;'>";
        // Convert the image to base64
        $imagePath = $decks['image'];
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;

        list($width, $height) = getimagesize($imagePath);
        $html .= '<div class="image-container" id="imgc' . $pageWidth . '" style="  position: relative;
        display: inline-block;width:100%">';
        $new_width  = 1024;
$new_height = 768;


if ($width > $height) {
  $image_height = floor(($height/$width)*$new_width);
  $image_width  = $new_width;
} else {
  $image_width  = floor(($width/$height)*$new_height);
  $image_height = $new_height;
}
        if ($width > 1000) {
            $newImage = '<img src="' . $imageBase64 . '" id="imageDraw' . $pageWidth . '" style="width:'.$image_width.'px" />';
            
          
            
        } else {
            $newImage = '<img src="' . $imageBase64 . '" id="imageDraw' . $pageWidth . '"  />';
        }
        $html .= $newImage;


        $html .= '<p>' . $decks["name"] . '</p>';

        $otherHtml = '';

        foreach ($decks['checks'] as $key => $value) {

            $top = $value->position_top;
            $left = $value->position_left;
            if ($width > 1000) {
                 $topshow =  ($image_width * $top) / $width;
            $leftshow = ($image_width * $left) / $width;
             
             $html .= '<div class="dot" style="top:' . $topshow . 'px;left:' . $leftshow . 'px; position: absolute;
                width: 12px;
                height: 12px;
                border: 2px solid #BF0A30;
                background: #BF0A30;
                border-radius: 50%;
                text-align: center;
                line-height: 20px;"></div>';

            }else{
                $html .= '<div class="dot" style="top:' . $top . 'px;left:' . $left . 'px; position: absolute;
                width: 12px;
                height: 12px;
                border: 2px solid #BF0A30;
                background: #BF0A30;
                border-radius: 50%;
                text-align: center;
                line-height: 20px;"></div>';
            }
           

        }
        $html .= '</div>';
        $html .= '</div>';
        $html = str_replace($otherHtml, '', $html);
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

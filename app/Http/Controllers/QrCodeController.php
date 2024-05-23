<?php

namespace App\Http\Controllers;

use App\Models\Checks;
use App\Models\Deck;
use App\Models\Projects;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use Dompdf\Options;

class QrCodeController extends Controller
{
    public function show($deckId)
    {
        // Fetch checks from the database
        $checks = Checks::select('id', 'name', \DB::raw('COALESCE(initialsChekId, "00000") as initialsChekId'))->where('deck_id', $deckId)->orderByDesc('id')->get();
        $deckDetail = Deck::select('name')->find($deckId);
      
        if ($checks->count() <= 0) {
            return redirect()->back()->with('message', 'This deck check not found.');
        }

        // Initialize Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // HTML content for PDF
        $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>QR Codes</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                    }
                    table {
                        width: 100%;
                        border: 1px solid #ddd;
                    }

                    th, td {
                        padding: 10px; /* Add padding to all sides of the cell */
                        text-align: center;
                        #border: 1px solid #ddd;
                        #border-left: none;
                    }

                    .parent td:last-child {
                        border-right: none;
                    }
                </style>
            </head>
            <body>';
            $html.= "<div><center><h3>Deck : $deckDetail[name]</h3></center></div>";

        $html .= '<table>';

        $html .= '<tbody>';

        $totalChecks = $checks->count();
        $colspan = 3;
        $counter = 0;
        $imagePath = asset('assets/images/logo.png');
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);
  
        foreach ($checks as $key => $check) {
            // Open a new row if it's the start of a new row
            if ($counter % $colspan == 0) {
                $html .= "<tr>";
            }

            // Generate QR code
            $qrCode = QrCode::size(75)->generate($check->name);
            $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qrCode);

            // Add the QR code and related information to table cells
            
            $html .= '<td width="13.33%">';
            $html .= '<div style="font-size: 15px;font-weight:bolder;padding-bootom:2px">' . $check->name . '</div>';
            $html .= '<img src=data:image/png;base64,"' . $base64Image . '" alt="QR Code for Check" width="110px;">';
            $html.='<center><div style="font-size: 12px;font-weight:bolder">www.sosindi.com</div>';
            $html .= '</td>';
            $html .= '<td class="qr-code" width="20%" style="border-right: 2px solid #ddd;">';
            $html .= '<img src="' . $qrCodeDataUri . '" alt="QR Code for Check" style="margin-bottom: 8px;">';
           

            $html .= '</td>';

            // Close the row if it's the end of a row or the last check
            if (($counter + 1) % $colspan == 0 || $key == $totalChecks - 1) {
                // Calculate remaining columns for colspan
                $remainingColumns = $colspan - (($totalChecks - $counter - 1) % $colspan) - 1;
                // print_r($remainingColumns);
                // Add empty cells to fill the remaining columns
                if ($remainingColumns > 0) {
                    $html .= '<td colspan="' . $remainingColumns * 2 . '"></td>';
                    // $html .= '<td width: 33.33% colspan="' . $remainingColumns . '"></td>';
                }

                // Close the row
                $html .= '</tr>';
            }

            $counter++;
        }

        $html .= '</tbody>';

        $html .= '</table>';

        $html .= '</body></html>';

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Output PDF
       //  return $dompdf->stream('qr_codes.pdf', ['Attachment' => false]);
        $pdfContent = $dompdf->output();
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="qr_codes_' . $deckDetail['name'] . '.pdf"');
    }
    public function generatorProjectQRcode($projectId){
        $checks = Checks::select('id', 'name', \DB::raw('COALESCE(initialsChekId, "00000") as initialsChekId'))->where('project_id', $projectId)->get();
        $deckDetail = Projects::select('ship_name')->find($projectId);
        if ($checks->count() <= 0) {
            return redirect()->back()->with('message', 'This deck check not found.');
        }

        // Initialize Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // HTML content for PDF
        $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>QR Codes</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                    }
                    table {
                        width: 100%;
                        border: 1px solid #ddd;
                    }

                    th, td {
                        padding: 10px; /* Add padding to all sides of the cell */
                        text-align: center;
                        #border: 1px solid #ddd;
                        #border-left: none;
                    }

                    .parent td:last-child {
                        border-right: none;
                    }
                </style>
            </head>
            <body>';
            $html.= "<div><center><h3>Deck : $deckDetail[ship_name]</h3></center></div>";

        $html .= '<table>';

        $html .= '<tbody>';

        $totalChecks = $checks->count();
        $colspan = 3;
        $counter = 0;
        $imagePath = asset('assets/images/logo.png');
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);
        $projectCount = 0;
        foreach ($checks as $key => $check) {
            $projectCount++;
            // Open a new row if it's the start of a new row
            if ($counter % $colspan == 0) {
                $html .= "<tr>";
            }
            $explode = explode("#",$check->name);
            $newName = $explode[0]."#". str_pad($projectCount, 3, 0, STR_PAD_LEFT) ;
            // Generate QR code
            $qrCode = QrCode::size(75)->generate($newName);
            $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qrCode);

            // Add the QR code and related information to table cells
            
            $html .= '<td width="13.33%">';
            $html .= '<div style="font-size: 15px;font-weight:bolder;padding-bootom:2px">' . $newName . '</div>';
            $html .= '<img src=data:image/png;base64,"' . $base64Image . '" alt="QR Code for Check" width="110px;">';
            $html.='<center><div style="font-size: 12px;font-weight:bolder">www.sosindi.com</div>';
            $html .= '</td>';
            $html .= '<td class="qr-code" width="20%" style="border-right: 2px solid #ddd;">';
            $html .= '<img src="' . $qrCodeDataUri . '" alt="QR Code for Check" style="margin-bottom: 8px;">';
           

            $html .= '</td>';

            // Close the row if it's the end of a row or the last check
            if (($counter + 1) % $colspan == 0 || $key == $totalChecks - 1) {
                // Calculate remaining columns for colspan
                $remainingColumns = $colspan - (($totalChecks - $counter - 1) % $colspan) - 1;
                // print_r($remainingColumns);
                // Add empty cells to fill the remaining columns
                if ($remainingColumns > 0) {
                    $html .= '<td colspan="' . $remainingColumns * 2 . '"></td>';
                    // $html .= '<td width: 33.33% colspan="' . $remainingColumns . '"></td>';
                }

                // Close the row
                $html .= '</tr>';
            }

            $counter++;
        }

        $html .= '</tbody>';

        $html .= '</table>';

        $html .= '</body></html>';

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
       $dompdf->render();

        // Output PDF
         return $dompdf->stream('qr_codes.pdf', ['Attachment' => false]);
         $pdfContent = $dompdf->output();
//   return response($pdfContent, 200)
            // ->header('Content-Type', 'application/pdf')
            // ->header('Content-Disposition', 'attachment; filename="qr_codes_' . $deckId . '.pdf"');
    }
}

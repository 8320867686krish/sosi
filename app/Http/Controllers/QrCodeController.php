<?php

namespace App\Http\Controllers;

use App\Models\Checks;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use Dompdf\Options;

class QrCodeController extends Controller
{
    public function show($deckId)
    {
        // Fetch checks from the database
        $checks = Checks::where('deck_id', $deckId)->orderByDesc('id')->get(['id', 'name']);

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
                        border: 1px solid #ddd;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .qr-code img {
                        max-width: 100px;
                        height: auto;
                    }
                </style>
            </head>
            <body>';

        $html .= '<table>';

        $html .= '<tbody>';

        $totalChecks = $checks->count();
        $counter = 0;

        foreach ($checks as $key => $check) {
            if ($counter % 6 == 0) {
                $html .= "<tr>";
            }

            $qrCode = QrCode::size(75)->generate($check->id);
            $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qrCode);
            $html .= '<td class="qr-code">';
            $html .= '<img src="' . $qrCodeDataUri . '" alt="QR Code for Check ' . $check->id . '" style="margin-bottom: 8px;">';
            $html .= '<span>' . $check->id . '</span>';
            $html .= '</td>';



            if (($counter + 1) % 6 == 0 || $key == $totalChecks - 1) {
                // Calculate colspan for the last row
                $remainingColumns = ($counter % 6) == 5 ? 1 : 6 - ($counter % 6);
                if ($remainingColumns != 1) {
                    $html .= '<td colspan="' . $remainingColumns . '"></td>';
                }
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
        // return $dompdf->stream('qr_codes.pdf', ['Attachment' => false]);
        $pdfContent = $dompdf->output();
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="qr_codes_' . $deckId . '.pdf"');
    }
}

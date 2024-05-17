<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Smalot\PdfParser\Parser;
use Throwable;

class PDFPasswordProtected implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // // Read the PDF file
        // $pdfContent = file_get_contents($value->path());

        // // Check if the PDF is password protected
        // if (strpos($pdfContent, '/Encrypt') !== false) {
        //     // If PDF is password protected, fail the validation
        //     $fail("This file appears to be password protected so cannot be uploaded.");
        // }

        // try {
        //     $parser = new Parser();
        //     $pdf = $parser->parseFile($value->path());

        //     // Check if the PDF is password protected
        //     if ($pdf->isEncrypted()) {
        //         $fail('This file appears to be password protected and cannot be uploaded.');
        //     }
        // } catch (Throwable $th) {
        //     // If an exception is thrown, we assume the PDF is encrypted or corrupted
        //     $fail('This file appears to be password protected or corrupted and cannot be uploaded.');
        // }

        $parser = new Parser();
        $pdf = $parser->parseFile($value->getPathname());

        // Get the document properties
        $properties = $pdf->getDetails();

        // Check if the PDF has digital signatures
        if (isset($properties['signatures'])) {
            $fail('This file appears to be password protected and cannot be uploaded.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\MultiSheetExport;
use App\Models\CheckHasHazmat;
use App\Models\User;
use App\Models\Hazmat;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportContoller extends Controller
{
    public function exportDataInExcel(Request $request)
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

        $filename = "customers-" . date('d-m-Y') . "." . $fileExt;
        return Excel::download(new MultiSheetExport(User::all(), Hazmat::all(), CheckHasHazmat::all()), $filename, $exportFormat);
    }
}

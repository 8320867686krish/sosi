<?php

namespace App\Http\Controllers;

use App\Exports\MultiSheetExport;
use App\Models\CheckHasHazmat;
use App\Models\User;
use App\Models\Hazmat;
use App\Models\Projects;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportContoller extends Controller
{
    public function exportDataInExcel(Request $request, $id)
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

        $hazmats = Hazmat::get();

        $userNames = $hazmats->map(function ($hazmat) {
            
            // return $user->name;
        });

        $filename = "projects-{$id}" . date('d-m-Y') . "." . $fileExt;
        return Excel::download(new MultiSheetExport($project, $hazmats), $filename, $exportFormat);
    }
}

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

        // $hazmats = Hazmat::withCount(['checkHasHazmats as check_type_count' => function ($query) use ($id) {
        //     $query->where('project_id', $id);
        // }])->get();

        // $hazmats = Hazmat::leftJoin('check_has_hazmats', 'hazmats.id', '=', 'check_has_hazmats.hazmat_id')
        //     ->where('check_has_hazmats.project_id', $id)
        //     ->select('hazmats.*', \DB::raw('COUNT(check_has_hazmats.id) as check_type_count'))
        //     ->groupBy('check_has_hazmats.id')
        //     ->get();

        $hazmats = Hazmat::with('checkHasHazmats')->withCount(['checkHasHazmats as check_type_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->get();

        // $countsByCheckType = $hazmats->map(function ($hazmat) {
        //     $hazmat->checkHasHazmats->select('check_type', \DB::raw('COUNT(*) as count'))
        //     ->groupBy('check_type')
        //     ->get();
        //     return $hazmat;
        // });

        $filename = "projects-{$id}" . date('d-m-Y') . "." . $fileExt;
        return Excel::download(new MultiSheetExport($project, $hazmats), $filename, $exportFormat);
    }
}

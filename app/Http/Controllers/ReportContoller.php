<?php

namespace App\Http\Controllers;

use App\Exports\MultiSheetExport;
use App\Models\CheckHasHazmat;
use App\Models\Checks;
use App\Models\User;
use App\Models\Hazmat;
use App\Models\Projects;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\Response;

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

        $hazmats = Hazmat::withCount(['checkHasHazmats as check_type_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->withCount(['checkHasHazmatsSample as sample_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->withCount(['checkHasHazmatsVisual as visual_count' => function ($query) use ($id) {
            $query->where('project_id', $id);
        }])->get();

        $checks = Checks::with('deck:id,name')->with('check_hazmats.hazmat')->where('project_id', $id);

        if ($isSample) {
            $checks = $checks->where('type', 'sample');
        }

        $checks = $checks->get();

        $filename = "projects-{$id}-" . time() . "." . $fileExt;
        return Excel::download(new MultiSheetExport($project, $hazmats, $checks), $filename, $exportFormat);
    }
    public function generateDocx()
    {
        
        $wordTest = new \PhpOffice\PhpWord\PhpWord();
 
        $newSection = $wordTest->addSection();
     
        $desc1 = "The Portfolio details is a very useful feature of the web page. You can establish your archived details and the works to the entire web community. It was outlined to bring in extra clients, get you selected based on this details.";
     
        $newSection->addText($desc1, array('name' => 'Tahoma', 'size' => 15, 'color' => 'red'));
        $catImageUrl = 'https://via.placeholder.com/150?text=Cat'; // Placeholder cat image URL
$newSection->addImage($catImageUrl);
        $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, 'Word2007');
        try {
            $objectWriter->save(storage_path('TestWordFile.docx'));
        } catch (Exception $e) {
        }
     
        return response()->download(storage_path('TestWordFile.docx'));
          


//         return response()->download(storage_path('helloWorld.docx'));
    }
}

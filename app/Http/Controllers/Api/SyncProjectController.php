<?php

namespace App\Http\Controllers\Api;

use App\Models\Projects;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Models\Deck;
use App\Models\Checks;
use App\Models\CheckImage;
use Carbon\Carbon;

class SyncProjectController extends Controller
{
    //
    public function syncProject(Request $request)
    {
        $projectId = $request->input('projectId');
        $syncDate = $request->input('syncDate');
        $user = Auth::user();

        $currentUserRoleLevel = $user->roles->first()->level;
        $myTime  = Carbon::parse($syncDate);
        $myTime = $myTime->setTimezone('UTC');

        // Convert $startDate to start of day

        if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
            return response()->json(['isStatus' => false, 'message' => 'Cant access.']);
        } else {
            $project = Projects::find($projectId);
            if ($syncDate != 0) {
                $decks = Deck::where('project_id', $projectId)
                    ->whereDate('updated_at', '>=', $myTime)
                    ->get();
           
                $checks = Checks::where('project_id', $projectId)
                    ->whereDate('updated_at', '>=', $myTime)
                    ->get();

                $checkImages = CheckImage::where('project_id', $projectId)
                    ->whereDate('updated_at', '>=', $myTime)
                    ->get();
            } else {
                $decks = Deck::where('project_id', $projectId)->get();
                $checks = Checks::where('project_id', $projectId)->get();
                $checkImages = CheckImage::where('project_id', $projectId)->get();
            }
            return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'projectList' => $project, 'decks' => $decks, 'checks' => $checks, 'checkImages' => $checkImages,'myTime'=>$myTime]);
        }
    }

    public function createZip(Request $request){
        $projectId = $request->input('projectId');
        $syncDate = $request->input('syncDate');
        $user = Auth::user();

        $currentUserRoleLevel = $user->roles->first()->level;
        $myTime = Carbon::parse($syncDate)->startOfDay(); // Convert $startDate to start of day

        if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
            return response()->json(['isStatus' => false, 'message' => 'Cant access.']);
        } else {
        }
    }

    public function removeZip($projectId)
    {
        $downLoadFile = public_path('images/pdf/' . $projectId . ".zip");
        if (file_exists($downLoadFile)) {
            unlink($downLoadFile);
        }
        return response()->json(['isStatus' => true, 'message' => 'pdf deleted successfully.']);
    }
}

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
        $myTime = Carbon::parse($syncDate)->startOfDay(); // Convert $startDate to start of day

        if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
            return response()->json(['isStatus' => false, 'message' => 'Cant access.']);
        } else {
            $project = Projects::find($projectId);
            if ($syncDate != 0) {
                // $decks = Deck::where('project_id', $projectId)
                //     ->whereDate('updated_at', '>=', $myTime)
                //     ->get();
                $decks = Deck::where('project_id', $projectId)
                ->whereDate('updated_at', '>=', $myTime)
                ->get();

                $lastUpdatedCheck = Checks::where('project_id', $projectId)
                ->orderBy('updated_at', 'desc')
                ->first();
            
            // Get the updated_at timestamp of the last updated record
            if ($lastUpdatedCheck) {
                $lastUpdateDate = $lastUpdatedCheck->updated_at->startOfDay(); // Get the start of the day for the last update date
            } else {
                // If there are no records for the project, use the input date as the last update date
                $lastUpdateDate = $myTime;
            }
              
                    $checks = Checks::where('project_id', $projectId)
                    ->whereDate('updated_at', '>=', $lastUpdateDate)
                    ->whereDate('updated_at', '<=', $myTime) // Filter by input date
                    ->get();
                $checkImages = CheckImage::where('project_id', $projectId)
                    ->whereDate('updated_at', '>=', $myTime)
                    ->get();
            } else {
                $decks = Deck::where('project_id', $projectId)->get();
                $checks = Checks::where('project_id', $projectId)->get();
                $checkImages = CheckImage::where('project_id', $projectId)->get();
            }
            return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'projectList' => $project, 'decks' => $decks, 'checks' => $checks, 'checkImages' => $checkImages]);
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

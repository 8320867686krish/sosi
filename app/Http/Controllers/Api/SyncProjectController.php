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


class SyncProjectController extends Controller
{
    //
    public function syncProject($projectId)
    {
        $user = Auth::user();

        $currentUserRoleLevel = $user->roles->first()->level;

        if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
            return response()->json(['isStatus' => false, 'message' => 'Cant access.']);

        } else {
                $downLoadFile = asset('images/pdf/'.$projectId.".zip");
                // $project = Projects::with(['client:id,manager_name,manager_logo,owner_name,owner_address', 'decks.checks' => function ($query) {
                //     $query->with(['check_image']);
                // }])->find($projectId);
                $project = Projects::find($projectId);
                $decks = Deck::where('project_id',$projectId)->get();
                $checks = Checks::where('project_id',$projectId)->get();
                $checkImages = CheckImage::where('project_id',$projectId)->get();
                $sourceDir = public_path('images/pdf/'.$projectId);
                $zipFilePath = public_path('images/pdf/'.$projectId.'.zip');
                $zip = new ZipArchive;
                if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
                    $files = File::files($sourceDir);
                
                    foreach ($files as $key => $value) {
                        $relativeNameInZipFile = basename($value);
                        $zip->addFile($value, $relativeNameInZipFile);
                    }
                
                    $zip->close();
                
                    // Check if the file exists before downloading
                    if (!file_exists($zipFilePath)) {
                        return response()->json(['isStatus'=>false,'error' => 'Failed to create zip file.']);
                    }
                } else {
                    // Handle error if the zip archive cannot be opened or created
                    return response()->json(['isStatus'=>false,'error' => 'Failed to create zip file.']);
                }
          
            
            return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'projectList' => $project,'decks'=>$decks,'checkImages'=>$checkImages,'zipPath'=>$downLoadFile,'checks'=>$checks]);
        }
      
    }
}

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
       
        

        // Convert $startDate to start of day

        if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
            return response()->json(['isStatus' => false, 'message' => 'Cant access.']);
        } else {
            $project = Projects::find($projectId);
            if ($syncDate != 0) {
                $tz_from = 'Asia/Kolkata';

                // Create a Carbon instance from the datetime string and set the timezone
                $carbonDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $syncDate, new \DateTimeZone($tz_from));
                
                // Convert the datetime to UTC timezone
                $carbonDateTime->setTimezone('UTC');
                
                // Get the datetime in UTC
                $dateTimeUTC = $carbonDateTime->toDateTimeString();
                $decks = Deck::where('project_id', $projectId)
                    ->where('updated_at', '>=', $dateTimeUTC )
                    ->get();
                 
                $checks = Checks::where('project_id', $projectId)
                    ->where('updated_at', '>=',$dateTimeUTC )
                    ->get();

                $checkImages = CheckImage::where('project_id', $projectId)
                    ->where('updated_at', '>=',$dateTimeUTC )
                    ->get();
            } else {
                $decks = Deck::where('project_id', $projectId)->get();
                $checks = Checks::where('project_id', $projectId)->get();
                $checkImages = CheckImage::where('project_id', $projectId)->get();
            }
            return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'projectList' => $project, 'decks' => $decks, 'checks' => $checks, 'checkImages' => $checkImages]);
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
            //now check syncdate or not
            $downLoadFile = asset('images/pdf/' . $projectId . ".zip");
            $sourceDir = public_path('images/pdf/'.$projectId);
            $zipFilePath = public_path('images/pdf/'.$projectId.'.zip');
            $zip = new ZipArchive;
            if ($syncDate == 0){
                if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
                    $files = File::files($sourceDir);
                    foreach ($files as $key => $value) {
                        $relativeNameInZipFile = basename($value);
                        $zip->addFile($value, $relativeNameInZipFile);
                    }
                    $zip->close();
                }
                return response()->json(['isStatus'=>true,'message' => 'Successfully zip download','zipPath' => $downLoadFile]);
            }else{
                $tz_from = 'Asia/Kolkata';
                $carbonDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $syncDate, new \DateTimeZone($tz_from));
                $carbonDateTime->setTimezone('UTC');
                $dateTimeUTC = $carbonDateTime->toDateTimeString();
                $checkImages = CheckImage::where('project_id', $projectId)
                ->where('updated_at', '>=',$dateTimeUTC )
                ->put('image')->toArray();
                print_r( $checkImages);
                exit();
                $zip->open($zipFilePath, ZipArchive::CREATE);

                foreach ($checkImages as $image) {
                    $imageFilename = basename($image->getOriginal('image'));
                    $path = public_path('images/pdf/' . $projectId . '/' . $imageFilename);
                    if (file_exists($path) && is_file($path)) {
                        $imageData = file_get_contents($path);
                        // Add image data to zip file with the same name
                        $zip->addFromString(basename($image->image), $imageData);
                    }
                }
                $zip->close();
                return response()->json(['isStatus'=>true,'message' => 'Successfully zip download','zipPath' => $downLoadFile]);

            }
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

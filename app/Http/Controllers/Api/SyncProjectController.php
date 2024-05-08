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
use App\Models\Client;

use PDO;

class SyncProjectController extends Controller
{
    //
    public function zipUpload(Request $request)
    {
        // Extract project_id from request parameters
        $project_id = $request->input('project_id');
    
        // Check if a file named 'image' has been uploaded
        if ($request->hasFile('image')) {
            $zipFile = $request->file('image');
    
            // Create a unique extraction path
            $extractPath = $project_id;
    
            // Initialize ZipArchive
            $zip = new ZipArchive;
    
            // Check if the zip file can be opened
            if ($zip->open($zipFile) === true) {
                // Extract the zip file
                if ($zip->extractTo('public/images/appImages/' . $extractPath)) {
                    $zip->close();
    
                    // Get the original name of the zip file
                    $zipFileName = $zipFile->getClientOriginalName();
    
                    // Extract the inner folder name
                    $innerFolderName = pathinfo($zipFileName, PATHINFO_FILENAME);
    
                    // Respond with success message
                    return response()->json(['isStatus' => true, 'message' => 'File uploaded and extracted successfully.', 'zipFileName' => $zipFileName, 'innerFolderName' => $innerFolderName]);
                } else {
                    // Respond with error message if extraction fails
                    return response()->json(['isStatus' => false, 'message' => 'Failed to extract zip file.']);
                }
            } else {
                // Respond with error message if zip file cannot be opened
                return response()->json(['isStatus' => false, 'message' => 'Failed to open zip file.']);
            }
        } else {
            // Respond with error message if no file is uploaded
            return response()->json(['isStatus' => false, 'message' => 'Please upload a file.']);
        }
    }
    
    public function syncProject(Request $request)
    {
        $projectId = $request->input('projectId');
        $user = Auth::user();
        $currentUserRoleLevel = $user->roles->first()->level;
        $client_id = Projects::select('client_id')->find($projectId);
        $client = Client::find($client_id)->toArray();
        $decks = Deck::where('project_id', $projectId)->get();
        $checks = Checks::where('project_id', $projectId)->get();
        $checkImages = CheckImage::where('project_id', $projectId)->get();
        return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'decks' => $decks, 'checks' => $checks, 'checkImages' => $checkImages]);
    }

    public function createZip(Request $request)
    {
        $projectId = $request->input('projectId');
        $syncDate = $request->input('syncDate');
        $timeZone = $request->input('timeZone');
        $user = Auth::user();

        $currentUserRoleLevel = $user->roles->first()->level;
        $myTime = Carbon::parse($syncDate)->startOfDay(); // Convert $startDate to start of day
        //now check syncdate or not
        $downLoadFile = asset('images/projects/' . $projectId . ".zip");
        $sourceDir = public_path('images/projects/' . $projectId);
        $zipFilePath = public_path('images/projects/' . $projectId . '.zip');
        $zip = new ZipArchive;
        if ($syncDate == 0) {
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
                $files = File::files($sourceDir);
                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
                $zip->close();
            }
           

            return response()->json(['isStatus' => true, 'message' => 'Successfully zip download', 'zipPath' => $downLoadFile]);
        } else {
            $tz_from = $timeZone;
            $carbonDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $syncDate, new \DateTimeZone($tz_from));
            $carbonDateTime->setTimezone('UTC');
            $dateTimeUTC = $carbonDateTime->toDateTimeString();
            $checkImages = CheckImage::where('project_id', $projectId)
                ->where('updated_at', '>=', $dateTimeUTC)
                ->pluck('image')->toArray();

            $decks = Deck::where('project_id', $projectId)
                ->where('updated_at', '>=', $dateTimeUTC)
                ->pluck('image')->toArray();

            $allImages = array_merge($checkImages, $decks);
            if (@$allImages) {
                $zip->open($zipFilePath, ZipArchive::CREATE);

                foreach ($allImages as $image) {
                    $imageFilename = basename($image);
                    $path = public_path('images/projects/' . $projectId . '/' . $imageFilename);
                    if (file_exists($path) && is_file($path)) {
                        $imageData = file_get_contents($path);
                        // Add image data to zip file with the same name
                        $zip->addFromString(basename($image), $imageData);
                    }
                }
                $zip->close();
                return response()->json(['isStatus' => true, 'message' => 'Successfully zip download', 'zipPath' => $downLoadFile]);
            } else {
                return response()->json(['isStatus' => false, 'message' => 'No file', 'zipPath' => '']);
            }
        }
    }

    public function removeZip($projectId)
    {
        $downLoadFile = public_path('images/projects/' . $projectId . ".zip");
        if (file_exists($downLoadFile)) {
            unlink($downLoadFile);
        }
        return response()->json(['isStatus' => true, 'message' => 'pdf deleted successfully.']);
    }

    public function syncAdd(Request $request)
    {
        $post = $request->input();
        if (@$post['checks']) {
            $checkIds = array_column($post['checks'], 'id');
            $dbChecks = Checks::where('project_id', $post['project_id'])->where('deck_id', $post['deck_id'])->pluck('id')->toArray();
            $arrdiff = array_diff($dbChecks, $checkIds);
            $checkDelete = Checks::whereIn('id', $arrdiff)->delete();

            $projectDetail = Projects::with(['client' => function ($query) {
                $query->select('id', 'manager_initials'); // Replace with the fields you want to select
            }])->withCount('checks')->find($post['project_id']);

            foreach ($post['checks'] as $value) {
                if (!in_array($value['id'], $dbChecks)) {
                    $lastCheck = Checks::where('project_id', $post['project_id'])
                        ->latest()
                        ->first();
                    if (!$lastCheck) {
                        $projectCount = "1001";
                    } else {
                        $projectCount = $lastCheck['initialsChekId'] + (1);
                    }
                    $name = "sos" . $projectDetail['client']['manager_initials'] . $projectCount;
                    $value['name'] = $name;
                    $value['initialsChekId'] =  $projectCount;
                    $value['isApp'] = 1;
                    $value['project_id'] = $post['project_id'];
                    $value['deck_id'] = $post['deck_id'];
                }
                Checks::updateOrCreate(['id' => $value['id']], $value);
            }
        }
    }
}

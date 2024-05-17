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
use App\Models\CheckHasHazmat;
use App\Models\Hazmat;
use Illuminate\Support\Facades\Log;
use PDO;

class SyncProjectController extends Controller
{
    //
    public function zipUpload(Request $request)
    {
        // Extract project_id from request parameters
        $projectId = $request->input('project_id');
        $project = Projects::select('client_id')->find($projectId);
        if (!$project) {
            return response()->json(['isStatus' => false, 'message' => 'Sorry project does not eist.']);
        }
        // Check if a file named 'image' has been uploaded
        if ($request->hasFile('image')) {
            $zipFile = $request->file('image');

            // Create a unique extraction path
            $extractPath = $projectId;

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
        $checks = Checks::with(['hazmats' => function ($query) {
            $query->with('hazmat:id,name');
        }])->where('project_id', $projectId)->get();
        $checksArray = $checks->map(function ($check) {
            return [
                'id' => $check->id,
                'project_id' => $check->project_id,
                'deck_id' => $check->deck_id,
                'type' => $check->type,
                'name' => $check->name,
                'equipment' => $check->equipment,
                'component' => $check->component,
                'location' => $check->location,
                'sub_location' => $check->sub_location,
                'pairWitthTag' => $check->pairWitthTag,
                'remarks' => $check->remarks,
                'position_left' => $check->position_left,
                'position_top' => $check->position_top,
                'initialsChekId' => $check->initialsChekId,
                'isApp' => $check->isApp,
                'isCompleted' => $check->isCompleted,
                'suspected_hazmat' => $check->hazmats->pluck('hazmat.name')->implode(', '),
            ];
        });
        $checkImages = CheckImage::where('project_id', $projectId)->get();
        return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'decks' => $decks, 'checks' => $checksArray, 'checkImages' => $checkImages]);
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
        $projectId = $post['projectId'];
        $checkImage = [];
        if (@$post['insertCheck']) {

            foreach ($post['insertCheck'] as $value) {
                $projectDetail = Projects::with(['client' => function ($query) {
                    $query->select('id', 'manager_initials'); // Replace with the fields you want to select
                }])->withCount('checks')->find($value['project_id']);

                $lastCheck = Checks::latest()->first();
                if (!$lastCheck) {
                    $projectCount = "1001";
                } else {
                    $projectCount = $lastCheck['initialsChekId'];
                }
                $name = $projectDetail['ship_initials'] . 'vsc#' . str_pad($projectCount + 1, 3, 0, STR_PAD_LEFT);

                $value['name'] = $name;
                $value['initialsChekId'] =  $projectCount;
                $value['isApp'] = 1;
                $value['project_id'] = $value['project_id'];
                $value['deck_id'] = $value['deck_id'];
                $checkAdd = Checks::updateOrCreate(['id' => $value['id']], $value);
                $checkId = $checkAdd->id;
                $checkImage[$value['id']] = $checkId;

                if (!empty($request->input('suspected_hazmat'))) {
                    $suspectedHazmat = explode(', ', $request->input('suspected_hazmat'));

                    foreach ($suspectedHazmat as $hazmat) {
                        $hazmatId = Hazmat::where('name', $hazmat)->first();

                        $hazmatData = [
                            "project_id" => $value['project_id'],
                            "check_id" => $checkId,
                            "hazmat_id" => $hazmatId->id,
                            "type" => "Unknown",
                            "check_type" => $value['type']
                        ];

                        CheckHasHazmat::create($hazmatData);
                    }
                }
            }
        }
        if (@$post['deletedCheck']) {
            foreach ($post['deletedData'] as $value) {
                $check = Checks::find($value['id']);
                $check->delete();
            }
        }
        if (@$post['checkHazImageInsert']) {
            foreach ($post['checkHazImageInsert'] as $value) {
                $exploded = explode('/', $value['image']);
                $appImages = public_path('images/appImages' . "/" . $value['project_id'] . "/" . end($exploded));
                $desiredPath = public_path('images/projects' . "/" . $value['project_id']);
                $newPath = $desiredPath . "/" . end($exploded);
                if (rename($appImages, $newPath)) {
                    $inputData['image'] = end($exploded);
                    $inputData['project_id'] = $value['project_id'];
                    if (isset($checkImage[$value['check_id']])) {
                        $check_id = $checkImage[$value['check_id']];
                        // Use $check_id_value as needed
                    }
                    $inputData['check_id'] =  $check_id ?? $value['check_id'];
                    $inputData['isCompleted'] = 1;

                    CheckImage::create($inputData);
                    Checks::where('id', $value['check_id'])->update(['isCompleted' => 1]);
                }
            }
        }

        if (@$post['checkHazImageDeleted']) {
            foreach ($post['checkHazImageDeleted'] as $value) {
                $checkImg = CheckImage::find($value['id']);
                $path = public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $checkImg->project_id . "/" . $checkImg->image);
                if (file_exists($path)) {
                    unlink($path);
                }
                $checkImg->delete();
                $checkData = Checks::withCount('check_image')->find($checkImg['check_id']);
                if ($checkData->check_image_count === 0) {
                    $checkData->update(['isCompleted' => 0]);
                }
            }
        }
        if (@$post['surveyUpdated']) {
            $projectData = $post['surveyUpdated'][0];
            $projectUpdateData['survey_location_name'] = $projectData['survey_location_name'];
            $projectUpdateData['survey_location_address'] = $projectData['survey_location_address'];
            $projectUpdateData['survey_type'] = $projectData['survey_type'];
            $projectUpdateData['survey_date'] = $projectData['survey_date'];
            Projects::where('id', $projectId)->update($projectUpdateData);
        }

        if (@$post['updatedCheck']) {
            foreach ($post['updatedCheck'] as $value) {
                Log::info($value);
                if ($value['suspected_hazmat']) {
                    Log::info($value['suspected_hazmat']);
                    $suspectedHazmat = explode(',', $value['suspected_hazmat']); // Corrected syntax: $value['suspected_hazmat'] instead of $value('suspected_hazmat')
                    $logArray = array_map('trim', $suspectedHazmat);

                    $hazmatIds = Hazmat::whereIn('name', $logArray)->pluck('id')->toArray();

                    CheckHasHazmat::where([
                        "project_id" => $value['project_id'],
                        "check_id" => $value['id'],
                    ])->whereNotIn('hazmat_id', $hazmatIds)->delete();

                    foreach ($hazmatIds as $hazmatId) {
                        $hazmatData = [
                            "project_id" => $value['project_id'],
                            "check_id" => $value['id'],
                            "hazmat_id" => $hazmatId,
                            "type" => "Unknown",
                            "check_type" => $value['type']
                        ];
                        $checkhasData = CheckHasHazmat::where('hazmat_id', $hazmatId)->where('check_id', $value['id'])->first();
                        if (!@$checkhasData) {
                            CheckHasHazmat::create($hazmatData);
                        }
                    }
                }
            }
        }
        return response()->json(['isStatus' => true, 'message' => 'successfully saved.']);
    }
}

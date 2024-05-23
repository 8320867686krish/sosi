<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignProjectRequest;
use App\Http\Requests\ProjectDetailRequest;
use App\Http\Requests\ProjectRequest;
use App\Models\CheckHasHazmat;
use App\Models\CheckImage;
use App\Models\Checks;
use App\Models\Client;
use App\Models\Deck;
use App\Models\Hazmat;
use App\Models\Attechments;
use App\Models\LabResult;
use App\Models\MakeModel;
use App\Models\Projects;
use App\Models\ProjectTeam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use setasign\Fpdi\Fpdi;
use Intervention\Image\Facades\Image as ImageIntervention;

class ProjectsController extends Controller
{
    public function index($client_id = null)
    {
        $user = Auth::user();
        $currentUserRoleLevel = $user->roles->first()->level;

        if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
            $projects = Projects::with('client:id,manager_name');
        } else {
            $projects = $user->projects()->with('client:id,manager_name,manager_logo')->where('isExpire', 0);
        }

        if ($client_id) {
            $projects->where('client_id', $client_id);
        }

        $projects = $projects->get();

        return view('projects.project', compact('projects'));
    }

    public function create()
    {
        $clients = Client::orderBy('id', 'desc')->get(['id', 'manager_name', 'manager_initials']);
        return view('projects.projectAdd', ['head_title' => 'Add', 'button' => 'Save', 'clients' => $clients]);
    }

    public function projectView($project_id)
    {

        $clients = Client::orderBy('id', 'desc')->get(['id', 'manager_name', 'manager_initials']);
        $isBack = 0;
        if (session('back') == 1) {
            $isBack = 1;
        }
        Session::forget('back');

        $role_id = Auth::user()->roles->first()->level;

        if ($role_id != 1) {
            $users = User::whereHas('roles', function ($query) use ($role_id) {
                $query->where('level', '>', $role_id)->orderBy('level', 'asc');
            })->where('isVerified', 1)->get();
        } else {
            $users = User::whereHas('roles', function ($query) {
                $query->where('level', '!=', 1)->orderBy('level', 'asc');
            })->where('isVerified', 1)->get();
        }

        $project = Projects::with([
            'project_teams',
            'client:id,manager_name,owner_name'
        ])->find($project_id);

        if ($project) {
            $project->decks = $project->decks()->orderBy('id', 'desc')->get();
            $project->checks = $project->checks()->with('check_hazmats.hazmat')->orderBy('id', 'desc')->get();
        }

        $attachment = Attechments::where('project_id', $project_id)->get();

        $project['imagePath'] = $project->image != null ? $project->image : asset('assets/images/giphy.gif');

        $project['user_id'] = $project->project_teams->pluck('user_id')->toArray();
        $project->assign_date = $project->project_teams->pluck('assign_date')->unique()->values()->toArray();
        $project->end_date = $project->project_teams->pluck('end_date')->unique()->values()->toArray();
        unset($project->project_teams);

        if (!Gate::allows('projects.edit')) {
            $readonly = "readOnly";
        } else {
            $readonly = "";
        }

        $table_type = Hazmat::select('table_type')->distinct()->pluck('table_type');

        $hazmats = [];

        foreach ($table_type as $type) {
            $hazmats[$type] = Hazmat::where('table_type', $type)->get(['id', 'name', 'table_type']);
        }

        return view('projects.projectView', ['head_title' => 'Ship Particulars', 'button' => 'View', 'users' => $users, 'clients' => $clients, 'project' => $project, 'readonly' => $readonly, 'project_id' => $project_id, 'isBack' =>  $isBack, "hazmats" => $hazmats, 'attachment' => $attachment]);
    }

    public function projectInfo($project_id)
    {
        return view('projects.projectInfo1', ['head_title' => 'Ship Particulars', 'button' => 'View']);
    }

    public function store(ProjectRequest $request)
    {
        try {
            $id = $request->input('id');
            $inputData = $request->input();

            Projects::updateOrCreate(['id' => $id], $inputData);

            $message = empty($id) ? "Project added successfully" : "Project updated successfully";

            return redirect('projects')->with('message', $message);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        try {
            $clients = Client::orderBy('id', 'desc')->get(['id', 'manager_name', 'manager_initials']);
            $project = Projects::find($id);
            return view('projects.projectAdd', ['head_title' => 'Edit', 'button' => 'Update', 'clients' => $clients, 'project' => $project]);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $project = Projects::findOrFail($id);

            $projectImagePath = public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $project->id);

            if (File::isDirectory($projectImagePath)) {
                File::deleteDirectory($projectImagePath);
            }

            $project->delete();

            return redirect('projects')->with('message', 'Project deleted successfully');
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function saveDetail(ProjectDetailRequest $request)
    {
        $inputData = $request->input();
        unset($inputData['manager_name']);
        unset($inputData['owner_name']);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();

            $image->move(public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $inputData['id'] . "/"), $imageName);

            $inputData['image'] = $imageName;

            if (!empty($inputData['id'])) {

                $exitsImg = Projects::select('id', 'image')->findOrFail($inputData['id']);
                $imageFilename = basename($exitsImg->getOriginal('image'));
                if (!empty($exitsImg->image)) {
                    $path = public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $inputData['id'] . '/' . $imageFilename);

                    if (file_exists($path) && is_file($path)) {
                        unlink($path);
                    }
                }
            }
        }

        $inputData['projectPercentage'] = "10";
        unset($inputData['_token']);
        Projects::where(['id' => $inputData['id']])->update($inputData);
        return response()->json(['isStatus' => true, 'message' => 'successfully save details !!']);
    }

    public function checkLaboratoryFile(Request $request)
    {
        // Get all files from the request
        $files = $request->allFiles();

        // Check if there are any files in the request
        if (!empty($files)) {
            foreach ($files as $inputName => $file) {
                // Check file type if needed
                $mimeType = $file->getMimeType();

                // Example: Check if it's a PDF file
                if ($mimeType === 'application/pdf') {
                    try {
                        // Initialize PDF instance
                        $pdf = new Fpdi('L');

                        // Get the file path
                        $filePath = $file->getPathName();

                        // Set source file
                        $pdf->setSourceFile($filePath);

                        // Return success response
                        return response()->json(["isStatus" => true, 'message' => 'File processed successfully.'], 200);
                    } catch (Throwable $e) {
                        // Return error response if file cannot be processed
                        return response()->json(["isStatus" => false, 'message' => 'Error processing the file.'], 500);
                    }
                } else {
                    // Return error response if file type is not supported
                    return response()->json(["isStatus" => false, 'message' => 'Unsupported file type.'], 415);
                }
            }
        } else {
            // Return error response if no file is found in the request
            return response()->json(["isStatus" => false, 'message' => 'No file uploaded.'], 400);
        }
    }


    public function assignProject(AssignProjectRequest $request)
    {
        try {
            $inputData = $request->input();
            $inputData['id'] = $inputData['project_id'];


            if (@$inputData['user_id']) {
                ProjectTeam::where('project_id', $inputData['project_id'])->delete();
                foreach ($inputData['user_id'] as $user_id) {
                    ProjectTeam::create([
                        'user_id' => $user_id,
                        'project_id' => $inputData['project_id'],
                        'assign_date' => $inputData['assign_date'],
                        'end_date' => $inputData['end_date'],
                        'isExpire' => 0
                    ]);
                }
            }
            if (@$inputData['project']) {
                $savData = $inputData['project'];
                $projectData =  Projects::find($inputData['id']);
                $proid = $projectData['id'];

                if ($request->has('project.leb1LaboratoryResult1')) {
                    if (@$projectData['leb1LaboratoryResult1']) {
                        $imagePath =  public_path("images/labResult" . "/" . $proid . "/" . $projectData['leb1LaboratoryResult1']);
                        // Check if the image file exists before attempting to delete

                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    $file = $request->file('project.leb1LaboratoryResult1');
                    $imageName = time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/labResult/' . $inputData['project_id']), "/" . $imageName);
                    $savData['leb1LaboratoryResult1'] =  $imageName;
                }

                if ($request->has('project.leb1LaboratoryResult2')) {
                    if (@$projectData['leb1LaboratoryResult2']) {
                        $imagePath =  public_path("images/labResult" . "/" . $proid . "/" . $projectData['leb1LaboratoryResult2']);
                        // Check if the image file exists before attempting to delete

                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    $file = $request->file('project.leb1LaboratoryResult2');
                    $imageName = time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/labResult/' . $inputData['project_id']), "/" . $imageName);
                    $savData['leb1LaboratoryResult2'] =  $imageName;
                }


                if ($request->has('project.leb2LaboratoryResult1')) {
                    if (@$projectData['leb2LaboratoryResult1']) {
                        $imagePath =  public_path("images/labResult" . "/" . $proid . "/" . $projectData['leb2LaboratoryResult1']);
                        // Check if the image file exists before attempting to delete

                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    $file = $request->file('project.leb2LaboratoryResult1');
                    $imageName = time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/labResult/' . $inputData['project_id']), "/" . $imageName);
                    $savData['leb2LaboratoryResult1'] =  $imageName;
                }


                if ($request->has('project.leb2LaboratoryResult2')) {

                    if (@$projectData['leb2LaboratoryResult2']) {
                        $imagePath =  public_path("images/labResult" . "/" . $proid . "/" . $projectData['leb2LaboratoryResult2']);
                        // Check if the image file exists before attempting to delete

                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    $file = $request->file('project.leb2LaboratoryResult2');
                    $imageName = time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/labResult/' . $inputData['project_id']), "/" . $imageName);
                    $savData['leb2LaboratoryResult2'] =  $imageName;
                }

                if ($request->has('project.leb2LabList')) {
                    if (@$projectData['leb2LabList']) {
                        $imagePath =  public_path("images/labResult" . "/" . $proid . "/" . $projectData['leb2LabList']);
                        // Check if the image file exists before attempting to delete

                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    $file = $request->file('project.leb2LabList');
                    $imageName = time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/labResult/' . $inputData['project_id']), "/" . $imageName);
                    $savData['leb2LabList'] =  $imageName;
                }

                Projects::where(['id' => $inputData['id']])->update($savData);
            }
            return response()->json(['isStatus' => true, 'message' => 'Project assign successfully!!']);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    public function removeLebDoc(Request $request)
    {
        $project_id = $request->input('project_id');
        $filed = $request->input('type');
        $projectData =  Projects::find($project_id);
        if (@$projectData[$filed]) {
            $imagePath =  public_path("images/labResult" . "/" . $project_id . "/" . $projectData[$filed]);
            // Check if the image file exists before attempting to delete

            if (file_exists($imagePath)) {
                unlink($imagePath);
                $projectData[$filed] = NULL;
                $projectData->save();
            }
            return response()->json(['isStatus' => true, 'message' => 'Document Remove successfully!!']);
        }
    }
    public function pdfcrop(Request $request)
    {
        return view('projects.pdfCrop');
    }

    public function deckBasedCheckView($id)
    {
        $deck = Deck::with('checks.hazmats')->find($id);

        $table_type = Hazmat::select('table_type')->distinct()->pluck('table_type');

        $hazmats = [];

        foreach ($table_type as $type) {
            $hazmats[$type] = Hazmat::where('table_type', $type)->get(['id', 'name', 'table_type']);
        }

        return view('check.check', ['deck' => $deck, 'hazmats' => $hazmats]);
    }

    public function setBackSession()
    {
        session(['back' => 1]);
        return 1;
    }

    public function checkBasedHazmat($id)
    {
        $check = Checks::with(['labResults' => function ($query) {
            $query->with('hazmat:id,name');
        }])->with(['hazmats' => function ($query) {
            $query->with('hazmat:id,name');
        }])->find($id);

        $hazmatIds = $check->hazmats->pluck('hazmat_id')->toArray();

        $collection = collect($check->hazmats);

        $groupedEquipment = $collection->map(function ($hazmatId) {
            $hazmat = Hazmat::with('equipment:id,hazmat_id,equipment')->find($hazmatId->hazmat->id);
            $hazmatId->hazmat->equipment = $hazmat->equipment->groupBy('equipment');
            return $hazmatId;
        });

        $hazmats = $check->hazmats;
        $labs = $check->labResults;

        $htmllist = view('check.checkAddModal', compact('hazmats'))->render();
        $labresult = view('check.labResultModal', compact('labs', 'hazmats'))->render();

        return response()->json(['html' => $htmllist, 'labResult' => $labresult, 'hazmatIds' => $hazmatIds, "check" => $check]);
    }

    public function getHazmatEquipment($hazmat_id)
    {
        try {
            $hazmat = Hazmat::with('equipment:id,hazmat_id,equipment')->find($hazmat_id);
            $groupedEquipment = $hazmat->equipment->groupBy('equipment');
            return response()->json(['isStatus' => true, 'message' => 'Equipment retrieved successfully.', 'equipments' => $groupedEquipment]);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getEquipmentBasedManufacturer($hazmat_id, $type)
    {
        try {
            $manufacturers = MakeModel::where('hazmat_id', $hazmat_id)->where('equipment', $type)->select('manufacturer')->distinct()->get();
            return response()->json(['isStatus' => true, 'message' => 'Equipment besed manufacturers retrieved successfully.', 'manufacturers' => $manufacturers]);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getManufacturerBasedDocumentData($hazmat_id, $equipment, $manufacturer)
    {
        try {
            $documentData = MakeModel::where('hazmat_id', $hazmat_id)->where('equipment', $equipment)->where('manufacturer', $manufacturer)->get();

            $data = $documentData->map(function ($document) {
                $document->modelmakepart = "{$document->model}-{$document->make}-{$document->part}";
                return $document;
            });

            return response()->json(['isStatus' => true, 'message' => 'Manufacturers besed document data retrieved successfully.', 'documentData' => $data]);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getPartBasedDocumentFile($id)
    {
        try {
            $documentFile = MakeModel::select('id', 'document1', 'document2')->find($id);

            return response()->json(['isStatus' => true, 'message' => 'Part besed document file retrieved successfully.', 'documentFile' => $documentFile]);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function checkBasedImage($id)
    {
        try {
            $checkImgs = CheckImage::where('check_id', $id)->get(['id', 'image', 'project_id']);
            return response()->json(['isStatus' => true, 'message' => 'Check images retrieved successfully.', 'checkImagesList' => $checkImgs]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addImageHotspots(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'type' => 'required',
            ]);

            $inputData = $request->input();
            $id = $request->input('id');
            $suspectedHazmat = $request->input('suspected_hazmat');
            $suspectedHazmatId = $request->input('hasid');
            $tableTypes = $request->input('table_type');
            $remarks = $request->input('remark');
            $images = $request->file('image');
            $modelmakepart = $request->input('modelmakepart');
            $doc = $request->file('doc');
            $IHM_type = $request->input('IHM_type');
            $IHM_part = $request->input('IHM_part');
            $unit = $request->input('unit');
            $number = $request->input('number');
            $total = $request->input('total');
            $lab_remarks = $request->input('lab_remarks');
            $labid = $request->input('labid');

            $projectDetail = Projects::with(['client' => function ($query) {
                $query->select('id', 'manager_initials'); // Replace with the fields you want to select
            }])->find($inputData['project_id']);

            if (!@$id) {
                $lastCheck = Checks::where('project_id', $inputData['project_id'])->latest()->first();

                if (!$lastCheck) {
                    $projectCount = "0";
                } else {
                    $projectCount = $lastCheck['initialsChekId'];
                }
                $name = $projectDetail['ship_initials'] . 'VSC#' . str_pad($projectCount + 1, 3, 0, STR_PAD_LEFT);

                $inputData['name'] = $name;
                $inputData['initialsChekId'] = str_pad($projectCount + 1, 3, 0, STR_PAD_LEFT);
            } else {
                unset($inputData['name']);
            }

            $data = Checks::updateOrCreate(['id' => $id], $inputData);

            // add check based hazmat
            if (!empty($suspectedHazmat)) {

                foreach ($suspectedHazmat as $value) {

                    $hazmatData = [
                        "project_id" => $inputData['project_id'],
                        "check_id" => $data->id,
                        "hazmat_id" => $value,
                        "type" => $tableTypes[$value],
                        "check_type" => $inputData['type']
                    ];

                    if (isset($modelmakepart[$value])) {
                        $documentFile = MakeModel::select('id', 'document1', 'document2')->find($modelmakepart[$value]);
                    } else {
                        $documentFile = null;
                    }

                    if (empty($documentFile)) {
                        // Check if there's an image for the current suspected hazmat
                        if (isset($images[$value])) {

                            $image = $images[$value];

                            $imageName = "hazmat_{$data->id}_" . time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();

                            // Move the uploaded image to the desired location
                            $image->move(public_path('images/hazmat/' . $inputData['project_id']), $imageName);

                            // Assign the image name to the corresponding hazmat data
                            $hazmatData['image'] = $imageName;
                        }

                        // Check if there's an doc for the current suspected hazmat
                        if (isset($doc[$value])) {

                            $docs = $doc[$value];

                            $docName = "hazmat_{$data->id}_" . time() . rand(10, 99) . '.' . $docs->getClientOriginalExtension();

                            // Move the uploaded image to the desired location
                            $docs->move(public_path('images/hazmat/' . $inputData['project_id']), $docName);

                            // Assign the image name to the corresponding hazmat data
                            $hazmatData['doc'] = $docName;
                        }
                    } else {
                        $document1 = "hazmat_{$data->id}_" . $documentFile->document1['name'];

                        $directoryPath = public_path('images/hazmat/' . $inputData['project_id']);

                        if (!File::isDirectory($directoryPath)) {
                            // If directory doesn't exist, create it
                            File::makeDirectory($directoryPath, 0755, true, true);
                        }

                        File::copy(public_path('images/modelDocument/' . $documentFile->document1['name']), $directoryPath . '/' . $document1);

                        $hazmatData['image'] = $document1;

                        if (!empty($documentFile->document2['name'])) {
                            $document2 = "hazmat_{$data->id}_" . $documentFile->document2['name'];

                            File::copy(public_path('images/modelDocument/' . $documentFile->document2['name']), $directoryPath . '/' . $document2);

                            $hazmatData['doc'] = $document2;
                        }
                    }

                    if ($tableTypes[$value] == 'Unknown') {
                        $hazmatData['image'] = NULL;
                        $hazmatData['doc'] = NULL;
                    }

                    if ($tableTypes[$value] == 'PCHM') {
                        $hazmatData['remarks'] = $remarks[$value];
                    }

                    if (!empty($suspectedHazmatId[$value])) {
                        CheckHasHazmat::updateOrCreate(['id' => $suspectedHazmatId[$value]], $hazmatData);
                    } else {
                        CheckHasHazmat::create($hazmatData);
                    }

                    if (isset($IHM_type[$value])) {

                        $labResultData = [
                            "project_id" => $inputData['project_id'],
                            "check_id" => $data->id,
                            "hazmat_id" => $value,
                            "type" => $IHM_type[$value],
                            "unit" => $unit[$value],
                            "number" => $number[$value],
                            "total" => $total[$value],
                            "lab_remarks" => $lab_remarks[$value]
                        ];

                        if ($IHM_type[$value] === "Contained" || $IHM_type[$value] === "PCHM") {
                            $labResultData["IHM_part"] = $IHM_part[$value];
                        }

                        if (!empty($labid[$value])) {
                            LabResult::updateOrCreate(['id' => $labid[$value]], $labResultData);
                        } else {
                            LabResult::create($labResultData);
                        }
                    }
                }
            }

            if (!empty($request->input('deselectId'))) {
                $deselectIds = explode(',', $request->input('deselectId'));
                foreach ($deselectIds as $deselectId) {
                    if (!empty($deselectId)) {
                        CheckHasHazmat::where("check_id", $data->id)->where('hazmat_id', $deselectId)->delete();
                        LabResult::where("check_id", $data->id)->where('hazmat_id', $deselectId)->delete();
                    }
                }
            }

            $updatedData = $data->getAttributes();
            $name = $updatedData['name'];
            $projectDetail->projectPercentage = "50";
            $projectDetail->save();

            if (!empty($inputData['deck_id'])) {
                $checks = Checks::where('deck_id', $inputData['deck_id'])->get();
                $project = Projects::with('checks.check_hazmats.hazmat')->find($inputData['project_id']);
                $trtd = view('projects.allcheckList', compact('project'))->render();

                $htmllist = view('check.checkList', compact('checks'))->render();
            }

            $message = empty($id) ? "Image check added successfully" : "Image check updated successfully";

            return response()->json(['isStatus' => true, 'message' => $message, "id" => $data->id, 'name' => $name, 'htmllist' => $htmllist ?? " ", "trtd" => $trtd ?? " "]);
        } catch (ValidationException $e) {
            return response()->json(['isStatus' => false, 'message' => $e->validator->errors()]);
        } catch (\Throwable $th) {
            \Log::error('Logout error: ' . $th->getMessage());
            return response()->json(['isStatus' => false, 'error' => $th->getMessage()]);
        }
    }

    public function removeHazmatDocument($hazmat_id, $type)
    {
        try {
            $document = CheckHasHazmat::find($hazmat_id);

            if (!$document) {
                return response()->json(["isStatus" => false, 'message' => 'Hazmat not found']);
            }

            $documentPath = public_path('images/hazmat/' . $document->project_id . "/") . basename($document->getOriginal($type));

            // Delete the file if it exists
            if (file_exists($documentPath)) {
                if (!unlink($documentPath)) {
                    throw new \Exception('Failed to delete the document file.');
                }
            }

            // Update the document's type to NULL
            $document->update([$type => NULL]);

            return response()->json(['isStatus' => true, 'message' => "Document deleted successfully"]);
        } catch (\Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => $th->getMessage()]);
        }
    }

    public function saveImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
                'project_id' => 'required|exists:projects,id',
            ]);

            $project = Projects::findOrFail($request->input('project_id'));
            $projectName = Str::slug($project->ship_name);
            $projectId = $request->input('project_id');
            $file = $request->file('image');

            if (!$file->isValid()) {
                throw new \Exception('Uploaded image is not valid.');
            }

            $mainFileName = "{$projectName}_" . rand(10, 99) . "_" . time() . ".png";
            $imagePath = public_path(env('IMAGE_COMMON_PATH', 'images/projects/') . $projectId . '/');
            $file->move($imagePath, $mainFileName);

            $updateProjectData = ['deck_image' => $mainFileName, 'projectPercentage' => '15'];

            if ($request->hasFile('ga_plan') && $request->file('ga_plan')->isValid()) {
                $pdfName = time() . "_gaplan." . $request->ga_plan->extension();
                $request->ga_plan->move($imagePath, $pdfName);

                $oldGaPlanPath = $imagePath . $project->ga_plan;
                if (@$project->ga_plan && file_exists($oldGaPlanPath)) {
                    unlink($oldGaPlanPath);
                }

                $updateProjectData['ga_plan'] = $pdfName;
            }

            Projects::where('id', $projectId)->update($updateProjectData);

            $areas = $request->input('areas');
            $areasArray = json_decode($areas, true);

            foreach ($areasArray as $area) {
                $x = $area['x'];
                $y = $area['y'];
                $width = $area['width'];
                $height = $area['height'];
                $text = $area['text'] ?? ' ';

                $croppedImageName = Str::slug($text) . "_{$width}_{$height}_" . time() . ".png";

                // Reload the main image resource for each crop to avoid issues with multiple crops
                $image = imagecreatefrompng($imagePath . $mainFileName);
                if (!$image) {
                    throw new \Exception("Failed to create image resource from {$mainFileName}");
                }

                $croppedImage = imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
                if ($croppedImage === FALSE) {
                    imagedestroy($image); // Clean up the image resource
                    throw new \Exception("Failed to crop the image at [x: $x, y: $y, width: $width, height: $height]");
                }

                if (imagepng($croppedImage, $imagePath . $croppedImageName) === FALSE) {
                    imagedestroy($croppedImage); // Clean up the cropped image resource
                    throw new \Exception("Failed to save the cropped image as {$croppedImageName}");
                }

                Deck::create([
                    'project_id' => $projectId,
                    'name' => $text,
                    'image' => $croppedImageName,
                ]);

                // Clean up the image resources
                imagedestroy($croppedImage);
                imagedestroy($image);
            }

            $decks = Deck::where('project_id', $projectId)->orderByDesc('id')->get();
            $html = view('projects.list_vsp_ajax', compact('decks'))->render();

            return response()->json(['status' => true, 'message' => 'Image saved successfully', 'html' => $html]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function updateDeckTitle(Request $request)
    {
        try {

            $updated = Deck::where('id', $request->input('id'))->update(['name' => $request->input('name')]);
            if ($updated) {
                // Fetch the updated record
                $deck = Deck::select('id', 'name')->find($request->input('id'));

                // Your Eloquent query executed by using get()

                if ($deck) {
                    return response()->json(["status" => true, "message" => "Deck updated successfully", 'deck' => $deck]);
                } else {
                    return response()->json(["status" => false, "message" => "Deck not found"]);
                }
            } else {
                return response()->json(["status" => false, "message" => "Failed to update deck"]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function deleteDeckImg($id)
    {
        try {
            // Find the deck by ID
            $deck = Deck::find($id);
            $projectId = $deck->project_id;

            // Check if the deck exists
            if (!$deck) {
                return response()->json(['error' => 'Deck not found'], 404);
            }

            // Construct the image path
            $imagePath = public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $projectId . "/") . basename($deck->getOriginal('image'));
            // Check if the image file exists before attempting to delete

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the deck
            $deck->delete();

            $decks = Deck::where('project_id', $projectId)->orderByDesc('id')->get();

            $html = view('projects.list_vsp_ajax', compact('decks'))->render();

            return response()->json(["status" => true, "message" => "Deck deleted successfully", 'html' => $html]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function deleteCheck($id)
    {
        try {
            $check = Checks::find($id);

            if (!$check) {
                return response()->json(["status" => false, 'message' => 'Check not found'], 404);
            }

            $deckId = $check->deck_id;

            $hazmats = CheckHasHazmat::where('check_id', $id)->get();

            $hazImagePath = public_path('images/hazmat/' . $check->project_id . "/");
            $prefixToRemove = "hazmat_{$check->id}_"; // Specify the prefix to remove

            $pattern = "{$hazImagePath}{$prefixToRemove}*";

            // Get matching files
            $files = glob($pattern);

            // Delete the matching files
            File::delete($files);

            // Delete the check
            $check->delete();

            $checks = Checks::where('deck_id', $deckId)->get();

            $htmldot = view('check.dot', compact('checks'))->render();
            $htmllist = view('check.checkList', compact('checks'))->render();

            return response()->json([
                "status" => true,
                "message" => "Check deleted successfully",
                'htmldot' => $htmldot,
                'htmllist' => $htmllist
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function attachmentSave(Request $request)
    {
        $post = $request->input();
        if (@$post['id']) {
            if ($request->hasFile('details')) {
                $data = Attechments::find($post['id']);
                $imagePath =  public_path("images/attachment" . "/" . $post['project_id'] . "/" . $data['documents']);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        if ($request->hasFile('details')) {
            $file = $request->file('details');
            $imageName = time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attachment/' . $post['project_id']), "/" . $imageName);
            unset($post['documents']);
            $post['documents'] =  $imageName;
        }

        Attechments::updateOrCreate(['id' => $post['id']], $post);
        $attachment = Attechments::where('project_id', $post['project_id'])->get();
        $html = view('projects.attachmentAjax', compact('attachment'))->render();
        return response()->json([
            'status' => true,
            'html' => $html,
            'message' => 'Save successfully.',
        ]);
    }

    public function attachmentRemove($id)
    {
        $attachment =  Attechments::find($id);
        $proid = $attachment['project_id'];
        $imagePath =  public_path("images/attachment" . "/" . $proid . "/" . $attachment['documents']);
        // Check if the image file exists before attempting to delete

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $attachment->delete();
        $attachment = Attechments::where('project_id', $proid)->get();
        $html = view('projects.attachmentAjax', compact('attachment'))->render();
        return response()->json([
            'status' => true,
            'html' => $html,
            'message' => 'Save successfully.',
        ]);
    }

    public function getProjectBasedCheck(Request $request, $project_id)
    {
        $checks = Checks::where('project_id', $project_id)->paginate($request->input('length'));

        return response()->json($checks);
    }

    public function changeCheckImgRotation(Request $request)
    {
        try {
            // Retrieve and validate inputs
            $imageId = $request->input('imageId');
            $rotation = intval($request->input('rotation'));

            // Fetch the image record from the database
            $image = CheckImage::findOrFail($imageId);
            $oldImagePath = public_path(env('IMAGE_COMMON_PATH', 'images/projects/') . $image->project_id . '/' . basename($image->getOriginal('image')));

            // Get the extension of the image
            $extension = pathinfo($oldImagePath, PATHINFO_EXTENSION);

            // Create an image resource from the image file
            switch (strtolower($extension)) {
                case 'jpeg':
                case 'jpg':
                    $source = imagecreatefromjpeg($oldImagePath);
                    break;
                case 'png':
                    $source = imagecreatefrompng($oldImagePath);
                    break;
                case 'gif':
                    $source = imagecreatefromgif($oldImagePath);
                    break;
                default:
                    return response()->json(['error' => 'Unsupported image format'], 400);
            }

            // Convert clockwise to counter-clockwise rotation (as imagerotate rotates counter-clockwise)
            $rotation = 360 - ($rotation % 360);

            // Rotate the image
            $rotate = imagerotate($source, $rotation, 0);

            // Define folder and new image path
            $newImageFolder = public_path(env('IMAGE_COMMON_PATH', 'images/projects/') . $image->project_id);
            if (!file_exists($newImageFolder)) {
                mkdir($newImageFolder, 0777, true);
            }

            $imageName = time() . rand(10, 99) . '.' . $extension;
            $newImagePath = $newImageFolder . '/' . $imageName;

            // Save the rotated image
            switch (strtolower($extension)) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($rotate, $newImagePath);
                    break;
                case 'png':
                    imagepng($rotate, $newImagePath);
                    break;
                case 'gif':
                    imagegif($rotate, $newImagePath);
                    break;
            }

            // Free up memory
            imagedestroy($source);
            imagedestroy($rotate);

            // Remove the old image
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Update the image name in the database
            $image->image = $imageName;
            $image->save();

            return response()->json(['success' => 'Image rotated and saved successfully', 'newPath' => $newImagePath], 200);
        } catch (Throwable $e) {
            \Log::error('Error rotating image: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the image'], 500);
        }
    }

    public function addCheckImage(Request  $request)
    {
        try {
            $projectId = $request->input('project_id');
            $checkId = $request->input('check_id');

            $checkData = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path(env('IMAGE_COMMON_PATH', "images/projects/") .  $projectId), $imageName);
                    $checkData['image'] = $imageName;
                    $checkData['check_id'] = $checkId;
                    $checkData['project_id'] = $projectId;
                    CheckImage::create($checkData);
                }
            }

            return response()->json(["isStatus" => true, 'message' => 'Check image saved successfully']);
        } catch (Throwable $th) {
            \Log::error('Check image add Error: ' . $th->getMessage());
            return response()->json(['error' => 'An error occurred while processing the image'], 500);
        }
    }

    public function deleteCheckImage($id)
    {
        try {
            $checkImg = CheckImage::find($id);

            if (!$checkImg) {
                return response()->json(['isStatus' => false, 'message' => 'Check image not found.']);
            }

            $path = public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $checkImg->project_id . "/" . basename($checkImg->getOriginal('image')));

            if (file_exists($path)) {
                unlink($path);
            }

            $checkImg->delete();

            return response()->json(['isStatus' => true, 'message' => 'Check image deleted successfully.']);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'error' => 'An error occurred while processing your request.']);
        }
    }

    public function reintialCheckIndex($projectId)
    {
        $checks = Checks::where('project_id', $projectId)->get();
        $projectCount = 0;
        foreach ($checks as $key => $check) {
            $projectCount++;
            $explode = explode("#", $check->name);
            $intial = str_pad($projectCount, 3, 0, STR_PAD_LEFT);
            $newName = $explode[0] . "#" . $intial;
            $update = Checks::where('id', $check['id'])->update(['name' => $newName, 'initialsChekId' =>  $intial]);
        }
        return response()->json(['success' => 'ReIntial successfully'], 200);
    }
}

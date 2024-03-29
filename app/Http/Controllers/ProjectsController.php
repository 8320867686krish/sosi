<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectDetailRequest;
use App\Http\Requests\ProjectRequest;
use App\Models\Client;
use App\Models\Image;
use App\Models\ImageHotspot;
use App\Models\Deck;
use App\Models\Projects;
use App\Models\ProjectTeam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

        if ($projects->isNotEmpty()) {
            $projects = $projects->map(function ($item) {
                $item->imagePath = $item->image ? url("images/ship/{$item->image}") : asset('assets/images/dribbble.png');
                return $item;
            });
        }

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
        $users = User::where('isVerified', 1)->get();
        $project = Projects::with('project_teams', 'client:id,manager_name,owner_name', 'decks:id,project_id,name,image')->find($project_id);
        $project['imagePath'] = $project->image != null ? url("images/ship/{$project->image}") : asset('assets/images/giphy.gif');

        $project['user_id'] = $project->project_teams->pluck('user_id')->toArray();
        $project->assign_date = $project->project_teams->pluck('assign_date')->unique()->values()->toArray();
        $project->end_date = $project->project_teams->pluck('end_date')->unique()->values()->toArray();
        unset($project->project_teams);

        if ($project->decks) {
            foreach ($project->decks as $deck) {
                $imagePath = public_path("images/pdf/{$project->id}/{$deck->image}");
                if (file_exists($imagePath)) {
                    $deck->imagePath = url("images/pdf/{$project->id}/{$deck->image}");
                }
            }
        }

        if (!Gate::allows('projects.edit')) {
            $readonly = "readOnly";
        } else {
            $readonly = "";
        }
        return view('projects.projectView', ['head_title' => 'Ship Particulars', 'button' => 'View', 'users' => $users, 'clients' => $clients, 'project' => $project, 'readonly' => $readonly, 'project_id' => $project_id]);
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
        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images/ship'), $imageName);

            $inputData['image'] = $imageName;

            if (!empty($inputData['id'])) {
                $exitsImg = Projects::select('id', 'image')->findOrFail($inputData['id']);

                if (!empty($exitsImg->image)) {
                    $path = public_path('images/ship/' . $exitsImg->image);

                    if (file_exists($path) && is_file($path)) {
                        unlink($path);
                    }
                }
            }
        }

        unset($inputData['_token']);
        Projects::where(['id' => $inputData['id']])->update($inputData);
        return response()->json(['isStatus' => true, 'message' => 'successfully save details !!']);
    }

    public function assignProject(Request $request)
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     'user_id' => 'required',
            // ],[
            //     'user_id.required' => 'Please select user.',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['message' => $validator->errors()->first()]);
            // }

            $inputData = $request->input();

            ProjectTeam::where('project_id', $inputData['project_id'])->delete();

            if (@$inputData['user_id']) {
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

            return response()->json(['isStatus' => true, 'message' => 'Project assign successfully!!']);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    public function pdfcrop(Request $request)
    {
        return view('projects.pdfCrop');
    }

    public function addImageHotspots(Request $request)
    {
        try {
            $id = $request->input('id');
            $inputData = $request->input();

            if ($request->hasFile('img_hotspot')) {
                $file = $request->file('img_hotspot');

                $imageName = time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/img_hotspot'), $imageName);

                $inputData['name'] = $imageName;

                if (!empty($id)) {
                    $exitingImg = Image::findOrFail($id);
                    $path = public_path('images/img_hotspot/' . $exitingImg->name);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }

            $image = Image::updateOrCreate(['id' => $id], $inputData);

            $dots = json_decode($request->input('dots'));
            $insertData = array_map(function ($item) use ($image) {
                $itemArray = (array) $item;
                $itemArray['image_id'] = $image->id;
                return $itemArray;
            }, $dots);

            ImageHotspot::where('image_id', $image->id)->delete();

            ImageHotspot::insert($insertData);

            $message = empty($id) ? "Image added successfully" : "Image updated successfully";

            return response()->json(['isStatus' => true, 'message' => $message, "id"=>$image->id]);
        } catch (\Throwable $th) {
            return response()->json(['isStatus' => false, 'error' => $th->getMessage()]);
        }
    }

    public function saveImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'project_id' => 'required|exists:projects,id',
            ]);

            $project = Projects::findOrFail($request->input('project_id'));
            $projectName = Str::slug($project->ship_name);
            $projectId = $request->input('project_id');
            $file = $request->file('image');
            if (!$file->isValid()) {
                throw new \Exception('Uploaded image is not valid.');
            }
            $mainFileName = "{$projectName}_" . time() . ".png";
            $file->move(public_path('images/pdf/' . $projectId . "/"), $mainFileName);
            $areas = $request->input('areas');

            Projects::where('id', $request->input('project_id'))->update(['deck_image' => $mainFileName]);

            $areasArray = json_decode($areas, true);

            $image = imagecreatefrompng(public_path('images/pdf/' . $projectId . '/' . $mainFileName));
            foreach ($areasArray as $area) {
                $x = $area['x'];
                $y = $area['y'];
                $width = $area['width'];
                $height = $area['height'];
                $text = $area['text'] ?? " ";

                $withoutSpacesName = Str::slug($text);
                $croppedImageName = "{$withoutSpacesName}_{$width}_{$height}_" . time() . ".png";

                $croppedImage = imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);

                if ($croppedImage) {
                    imagepng($croppedImage, public_path("images/pdf/{$projectId}/{$croppedImageName}"));

                    Deck::create([
                        'project_id' => $request->input('project_id'),
                        'name' => $text,
                        'image' => $croppedImageName
                    ]);
                }
            }

            $decks = Deck::where('project_id', $request->input('project_id'))->orderByDesc('id')->get();

            if ($decks) {
                foreach ($decks as $deck) {
                    $imagePath = public_path("images/pdf/{$projectId}/{$deck->image}");
                    if (file_exists($imagePath)) {
                        $deck->imagePath = url("images/pdf/{$projectId}/{$deck->image}");
                    }
                }
            }

            $html = view('projects.list_vsp_ajax', compact('decks'))->render();

            return response()->json(["status" => true, "message" => "Image saved successfully", 'html' => $html]);
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
            $imagePath = public_path("images/pdf/{$projectId}/{$deck->image}");

            // Check if the image file exists before attempting to delete
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the deck
            $deck->delete();

            $decks = Deck::where('project_id', $projectId)->orderByDesc('id')->get();

            if ($decks) {
                foreach ($decks as $deck) {
                    $imagePath = public_path("images/pdf/{$projectId}/{$deck->image}");
                    if (file_exists($imagePath)) {
                        $deck->imagePath = url("images/pdf/{$projectId}/{$deck->image}");
                    }
                }
            }

            $html = view('projects.list_vsp_ajax', compact('decks'))->render();

            return response()->json(["status" => true, "message" => "Deck deleted successfully", 'html' => $html]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}

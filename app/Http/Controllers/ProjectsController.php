<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectDetailRequest;
use App\Http\Requests\ProjectRequest;
use App\Models\Client;
use App\Models\Projects;
use App\Models\ProjectTeam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProjectsController extends Controller
{
    public function index($client_id = null)
    {
        $user = Auth::user();

        $currentUserRoleLevel = $user->roles->first()->level;

        if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
            $projects = Projects::with('client:id,manager_name');
        } else {
            // $team = ProjectTeam::with('projects')->where('user_id',$user['id'])->get();
            $projects = $user->projects()->with('client:id,manager_name,manager_logo');
        }

        if (@$client_id) {
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
        $users = User::where('isVerified', 1)->get();

        $project = Projects::with('project_teams')->with('client:id,manager_name,owner_name')->find($project_id);
        $project['imagePath'] = $project->image != null ? url("images/ship/{$project->image}") : asset('assets/images/giphy.gif');
        // dd($project);
        $project['user_id'] = $project->project_teams->pluck('user_id')->toArray();
        $project->assign_date = $project->project_teams->pluck('assign_date')->unique()->values()->toArray();
        $project->end_date = $project->project_teams->pluck('end_date')->unique()->values()->toArray();

        unset($project->project_teams);

        if (!Gate::allows('projects.edit')) {
            $readonly = "readOnly";
        } else {
            $readonly = "";
        }
        return view('projects.projectView', ['head_title' => 'Ship Particulars', 'button' => 'View', 'users' => $users, 'clients' => $clients, 'project' => $project, 'readonly' => $readonly, 'project_id' => $project_id]);
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
}

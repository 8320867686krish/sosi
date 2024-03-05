<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectDetailRequest;
use App\Http\Requests\ProjectRequest;
use App\Models\Projects;
use App\Models\ProjectTeam;
use App\Models\shipOwners;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectsController extends Controller
{
    public function index($ship_owner_id = null)
    {
        $projects = Projects::with('ship_owner:id,name');
        if(@$ship_owner_id){
            $projects->where('ship_owners_id',$ship_owner_id);
        }
        $projects = $projects->get();
        return view('projects.project', compact('projects'));
    }

    public function create()
    {
        $owners = shipOwners::orderBy('id', 'desc')->get(['id', 'name', 'identification']);
        return view('projects.projectAdd', ['head_title' => 'Add', 'button' => '<i class="fas fa-plus"></i>  Add', 'owners' => $owners]);
    }

    public function projectView($project_id){
        $owners = shipOwners::orderBy('id', 'desc')->get(['id', 'name', 'identification']);
        $users = User::where('isVerified', 1)->get();

        $project = Projects::with('project_teams')->find($project_id);
        $project['user_id'] = $project->project_teams->pluck('user_id')->toArray();

        unset($project->project_teams);

        if (!Gate::allows('projects.edit')) {
          $readonly = "readOnly";
        }else{
            $readonly = "";
        }
        return view('projects.projectView', ['head_title' => 'Ship Particulars', 'button' => 'View', 'users'=>$users, 'owners' => $owners,'project'=>$project,'readonly'=>$readonly,'project_id'=>$project_id]);

    }
    public function store(ProjectRequest $request)
    {
        try {
            $id = $request->input('id');
            $inputData = $request->input();

            $ship_owner = shipOwners::where('id', $inputData['ship_owners_id'])->first(['id', 'identification']);

            // if ($ship_owner->count() > 0) {
            //     $inputData['project_no'] = "sosi_{$ship_owner->identification}_{$inputData['imo_number']}";
            // }

            Projects::updateOrCreate(['id' => $id], $inputData);

            $message = empty($id) ? "Project added successfully" : "Project updated successfully";

            return redirect('projects')->with('message', $message);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function edit(string $id){
        try {
            $owners = shipOwners::orderBy('id', 'desc')->get(['id', 'name', 'identification']);
            $project = Projects::find($id);

            return view('projects.projectAdd', ['head_title' => 'Edit', 'button' => 'Update', 'owners' => $owners, 'project'=>$project]);

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

    public function saveDetail(ProjectDetailRequest $request){
        $inputData = $request->input();
        unset($inputData['_token']);
        Projects::where(['id' => $inputData['id']])->update($inputData);
        return response()->json(['isStatus' => true, 'message' => 'successfully save details !!']);
    }

    public function assignProject(ProjectDetailRequest $request){
        $inputData = $request->input();

        if(@$inputData['user_id']){
            foreach($inputData['user_id'] as $user_id) {
                $count = ProjectTeam::where('user_id', $user_id)->where('project_id', $inputData['project_id'])->count();
                if ($count <= 0) {
                    ProjectTeam::create([
                        'user_id' => $user_id,
                        'project_id' => $inputData['project_id'],
                        'assign_date' => $inputData['assign_date'],
                        'end_date' => $inputData['end_date']
                    ]);
                }
            }
        }

        return response()->json(['isStatus' => true, 'message' => 'Project assign successfully!!']);
    }
}

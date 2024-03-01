<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Projects;
use App\Models\shipOwners;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Projects::with('ship_owner:id,name')->get();
        return view('projects.project', compact('projects'));
    }

    public function create()
    {
        $owners = shipOwners::orderBy('id', 'desc')->get(['id', 'name', 'identification']);
        return view('projects.projectAdd', ['head_title' => 'Add', 'button' => 'Add', 'owners' => $owners]);
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
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Projects;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SyncProjectController extends Controller
{
    //
    public function syncProject()
    {
        $user = Auth::user();

        $currentUserRoleLevel = $user->roles->first()->level;

        if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
            $project = Projects::with(['client:id,manager_name,manager_logo', 'decks.checks' => function ($query) {
                $query->with(['check_image', 'checkQrCodePair']);
            }]);
        } else {
        
            $project = $user->projects()
                ->with(['client:id,manager_name,manager_logo', 'decks.checks' => function ($query) {
                    $query->with(['check_image', 'checkQrCodePair']);
                }])
                ->where('isExpire', 0);
        }
        $project = $project->get();
        if ($project->count() > 0) {
            $modifiedProjects = $project;
        }
        return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'projectList' => $modifiedProjects]);
    }
}

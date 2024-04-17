<?php

namespace App\Http\Controllers\Api;

use App\Models\Projects;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            $projectList = collect();
            Projects::with(['client:id,manager_name,manager_logo', 'decks.checks' => function ($query) {
                $query->with(['check_image']);
            }])->where('id', $projectId)->chunk(100, function ($projects) use (&$projectList) {
                $projectList = $projectList->merge($projects);
            }); // Adjust chunk size as needed
        
           
          
            
            return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'projectList' => $projectList]);
        }
      
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTeam extends Model
{
    use HasFactory;


    protected $guarded = []; // This means no attributes are guarded
    public function projects()
    {
        return $this->belongsToMany(Projects::class, 'project_teams', 'project_id', 'id');
    }
}

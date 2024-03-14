<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    protected $table = "projects";

    protected $guarded = []; // This means no attributes are guarded

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function project_teams()
    {
        return $this->hasMany(ProjectTeam::class, 'project_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_teams', 'project_id', 'user_id');
    }

    public function getImageAttribute($value)
    {
        $imagePath = $value ? url("images/ship/{$value}") : asset('assets/images/dribbble.png');
        return $imagePath;
        // url("images/ship/{$value}");
    }
}

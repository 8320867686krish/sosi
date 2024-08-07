<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    protected $table = "projects";

    protected $guarded = []; // This means no attributes are guarded
    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];
    public function getImageAttribute($value)
    {
        return asset(env('IMAGE_COMMON_PATH', "images/projects/") . $this->id . '/' . $value);
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function project_teams()
    {
        return $this->hasMany(ProjectTeam::class, 'project_id', 'id');
    }

    public function decks()
    {
        return $this->hasMany(Deck::class, 'project_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_teams', 'project_id', 'user_id');
    }
    public function checks()
    {
        return $this->hasMany(Checks::class, 'project_id', 'id');
    }

    public function materials() {
        return $this->hasMany(ReportMaterial::class, 'project_id');
    }
}

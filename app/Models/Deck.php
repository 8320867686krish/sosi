<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = ["project_id", "name", "image"];

    protected $hidden = ['created_at', 'updated_at'];

    public function checks()
    {
        return $this->hasMany(Checks::class, 'deck_id', 'id');
    }

    public function getImageAttribute($value) {
        return asset(env('IMAGE_COMMON_PATH', "images/projects/") . $this->project_id . "/" . $value);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckHasHazmat extends Model
{
    use HasFactory;

    protected $fillable = ["project_id", "check_id", "hazmat_id", "image", "type", "check_type"];

    public function getImageAttribute($value){
        return asset(env('IMAGE_COMMON_PATH', "images/projects/") . $this->project_id . "/" . $value);
    }

    public function hazmat()
    {
        return $this->belongsTo(Hazmat::class, 'hazmat_id', 'id');
    }
}

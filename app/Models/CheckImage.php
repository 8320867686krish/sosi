<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckImage extends Model
{
    use HasFactory;

    protected $table = "check_has_images";

    protected $fillable = ['check_id', 'image','project_id'];

    protected $hidden = ['created_at', 'updated_at'];
    public function getImageAttribute($value){
        return asset("images/pdf/{$this->project_id}/{$value}");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name'];

    public function image_hotspots()
    {
        return $this->hasMany(ImageHotspot::class);
    }

}

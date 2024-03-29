<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageHotspot extends Model
{
    use HasFactory;

    protected $fillable = ['image_id', 'left', 'top'];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}

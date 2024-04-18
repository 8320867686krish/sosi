<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckHasHazmat extends Model
{
    use HasFactory;

    protected $fillable = ["check_id", "hazmat_id", "image", "type"];

    // public function getImageAttribute($value) {
    //     return asset("images/checks/{$this->check_id}/hazmat/{$value}");
    // }

    public function getImageAttribute($value)
    {
        $basePath = "images/checks/{$this->check_id}/hazmat/";
        $imagePath = asset($basePath . $value);
        $imageName = basename($value); // Extracts the image name from the path

        return [
            'path' => $imagePath,
            'name' => $imageName
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckHasHazmat extends Model
{
    use HasFactory;

    protected $fillable = ["check_id", "hazmat_id", "image", "type","project_id"];



    public function getImageAttribute($value)
    {
        $basePath = "images/pdf/{$this->project_id}/";
        $imagePath = asset($basePath . $value);
        $imageName = basename($value); // Extracts the image name from the path

        return [
            'path' => $imagePath,
            'name' => $imageName
        ];
    }

    public function hazmat()
    {
        return $this->belongsTo(Hazmat::class, 'hazmat_id', 'id');
    }
}

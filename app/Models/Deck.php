<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = ["project_id", "name", "image"];

    protected $hidden = ['created_at', 'updated_at'];

    // public function getImageAttribute($value)
    // {
    //     // Ensure $value is not null
    //     if ($value) {
    //         // Assuming $project_id is available in the context, otherwise replace it with the correct variable
    //         $project_id = $this->project_id;

    //         // Return the asset URL for the image
    //         return asset("images/projects/pdf/{$project_id}/{$value}");
    //     }

    //     // Return a default image or handle the case where $value is null
    //     return asset('path/to/default/image.jpg');
    // }


    public function checks()
    {
        return $this->hasMany(Checks::class, 'deck_id', 'id');
    }

    public function getImageAttribute($value){
        return asset("images/pdf/{$this->project_id}/{$value}");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checks extends Model
{
    use HasFactory;

    protected $table = "checks";

    protected $casts = [
        'isCompleted' => 'boolean',
    ];

    protected $fillable = ["project_id", "deck_id", "type", "name", "equipment", "component", "location", "sub_location", "remarks", "position_left", "position_top", "pairWitthTag", "isApp", 'initialsChekId', 'isCompleted'];

    protected $hidden = ['created_at', 'updated_at'];

    public function check_image()
    {
        return $this->hasMany(CheckImage::class, 'check_id', 'id');
    }

    public function hazmats()
    {
        return $this->hasMany(CheckHasHazmat::class, 'check_id', 'id');
    }

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }

    // public function getSuspectedHazmatAttribute($value){
    //     if (!@$value) {
    //         return []; // Return an empty array if the value is null
    //     }

    //     // Replace single quotes with double quotes to ensure valid JSON
    //     $jsonString = str_replace("'", '"', $value);

    //     // Decode the JSON string into a PHP array
    //     $decodedArray = json_decode($jsonString, true);

    //     // Check if the decoded value is an array
    //     return $decodedArray;
    // }
}

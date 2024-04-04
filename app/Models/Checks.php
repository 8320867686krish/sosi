<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checks extends Model
{
    use HasFactory;

    protected $table = "checks";

    protected $fillable = ["project_id", "deck_id", "type", "name", "description", "compartment", "material", "color", "suspected_hazmat", "equipment", "component", "position", "sub_position", "remarks", "position_left", "position_top","pairWitthTag"];

    protected $hidden = ['created_at', 'updated_at'];

    public function check_image(){
        return $this->hasMany(CheckImage::class, 'check_id', 'id');
    }
    public function deck(){
        return $this->belongsTo(Deck::class);
    }
    public function getpairWitthTagAttribute($value){
        return json_decode($value,true);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checks extends Model
{
    use HasFactory;

    protected $table = "checks";

    protected $fillable = ["project_id", "deck_id", "type", "name", "equipment", "component", "location", "sub_location", "remarks", "recommendation", "position_left", "position_top", "pairWitthTag", "isApp", 'initialsChekId', 'isCompleted'];

    protected $hidden = ['created_at', 'updated_at'];

    public function check_image()
    {
        return $this->hasMany(CheckImage::class, 'check_id', 'id');
    }

    public function hazmats()
    {
        return $this->hasMany(CheckHasHazmat::class, 'check_id', 'id');
    }

    public function check_hazmats()
    {
        //  return $this->belongsToMany(Hazmat::class, CheckHasHazmat::class, 'check_id');
        return $this->hasMany(CheckHasHazmat::class, 'check_id', 'id');
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class, 'check_id', 'id');
    }

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }
}

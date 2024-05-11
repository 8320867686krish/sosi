<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazmat extends Model
{
    use HasFactory;

    protected $fillable = ["name", "short_name", "table_type", "color"];

    protected $hidden = ['created_at', 'updated_at', 'color'];

    public function checkHasHazmats(){
        return $this->hasMany(CheckHasHazmat::class);
    }

    public function checkHasHazmatsSample(){
        return $this->hasMany(CheckHasHazmat::class)->where('check_type', 'sample');
    }

    public function checkHasHazmatsVisual(){
        return $this->hasMany(CheckHasHazmat::class)->where('check_type', 'visual');
    }

    public function equipment(){
        return $this->hasMany(MakeModel::class);
    }
}

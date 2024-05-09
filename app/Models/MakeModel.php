<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeModel extends Model
{
    use HasFactory;

    protected $fillable = ["hazmat_id", "equipment", "model", "make", "manufacturer", "part", "document1", "document2"];

    public function hazmat(){
        return $this->belongsTo(Hazmat::class);
    }

    public function getDocument1Attribute($value){
        return asset("images/modelDocument/{$value}");
    }

    public function getDocument2Attribute($value){
        return asset("images/modelDocument/{$value}");
    }
}

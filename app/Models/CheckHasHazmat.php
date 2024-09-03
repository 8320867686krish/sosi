<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckHasHazmat extends Model
{
    use HasFactory;

    protected $fillable = ["project_id", "check_id", "hazmat_id", "image", "type", "check_type","doc","remarks"];

    public function getImageAttribute($value){
        if($value){
            return asset('images/hazmat/' . $this->project_id . "/" . $value);

        }else{
            return false;
        }
        
    }

    public function getDocAttribute($value){
        return asset('images/hazmat/' . $this->project_id . "/" . $value);
    }

    public function hazmat()
    {
        return $this->belongsTo(Hazmat::class, 'hazmat_id', 'id');
    }
    public function FinalLebResult(){
        return $this->belongsTo(LabResult::class, 'check_id', 'check_id');

    }
}

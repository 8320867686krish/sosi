<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    use HasFactory;

    protected $fillable = ["project_id", "check_id", "hazmat_id", "type", "IHM_part", "unit", "number", "total", "lab_remarks"];

    protected $hidden = ['created_at', 'updated_at'];

    public function hazmat()
    {
        return $this->belongsTo(Hazmat::class, 'hazmat_id', 'id');
    }
    public function check()
    {
        return $this->belongsTo(Checks::class);
    }
   
}

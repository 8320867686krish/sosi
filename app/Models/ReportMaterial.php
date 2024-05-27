<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportMaterial extends Model
{
    use HasFactory;

    protected $fillable = ["project_id", "material", "structure", "make", "component", "type", "remark"];
}

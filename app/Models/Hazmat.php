<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazmat extends Model
{
    use HasFactory;

    protected $fillable = ["name", "table_type", "color"];
}

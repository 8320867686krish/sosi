<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipOwners extends Model
{
    use HasFactory;

    protected $table = "ship_owners";

    protected $fillable = ["name", "email", "phone", "address", "image", "identification"];
}

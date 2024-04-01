<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checks extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function check_image() {
        return $this->hasMany(CheckImage::class, 'check_id', 'id');
    }
}

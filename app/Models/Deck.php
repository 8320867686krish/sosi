<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = ["project_id", "name", "image"];
    public function checks()
    {
        return $this->hasMany(Checks::class,'deck_id', 'id');
    }
}

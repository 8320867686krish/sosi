<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function projects()
    {
        return $this->hasMany(Projects::class, 'client_id', 'id');
    }
    public function getManagerLogoAttribute($value){
        return asset("images/client/{$value}");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    protected $table = "projects";

    protected $guarded = []; // This means no attributes are guarded

    public function ship_owner(){
        // return $this->belongsTo(shipOwners::class);
        return $this->belongsTo(shipOwners::class, 'ship_owners_id', 'id');
    }
}

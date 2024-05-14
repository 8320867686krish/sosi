<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeModel extends Model
{
    use HasFactory;

    protected $fillable = ["hazmat_id", "equipment", "model", "make", "manufacturer", "part", "document1", "document2"];

    protected $hidden = ['created_at', 'updated_at'];

    public function hazmat()
    {
        return $this->belongsTo(Hazmat::class);
    }

    public function getDocument1Attribute($value)
    {
        return [
            'name' => $value,
            'path' => env('APP_URL') . "/images/modelDocument/{$value}",
        ];
    }

    public function getDocument2Attribute($value)
    {
        return [
            'name' => $value,
            'path' => env('APP_URL') . "/images/modelDocument/{$value}",
        ];
    }
}

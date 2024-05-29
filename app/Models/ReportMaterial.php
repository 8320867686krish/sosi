<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportMaterial extends Model
{
    use HasFactory;

    protected $fillable = ["project_id", "material", "structure", "make", "component", "type", "remark"];

    protected $hidden = ['created_at', 'updated_at'];

    public function getMakeAttribute($value)
    {
        // Attempt to decode the JSON value
        $decoded = json_decode($value, true);

        // Check for JSON errors and return an empty array or the decoded value
        return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }

    public function getComponentAttribute($value)
    {
        return explode(', ', $value);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentMaterial extends Model
{
    protected $fillable = [

        'department_id',

        'material_id',

        'quantity',

        'request_id',

        'released_by',

        'released_at'

    ];

    public function department()
    {
        return $this->belongsTo(
            Department::class
        );
    }

    public function material()
    {
        return $this->belongsTo(
            Material::class
        );
    }

    public function request()
    {
        return $this->belongsTo(
            MaterialRequest::class,
            'request_id'
        );
    }

    public function releaser()
    {
        return $this->belongsTo(
            User::class,
            'released_by'
        );
    }
}
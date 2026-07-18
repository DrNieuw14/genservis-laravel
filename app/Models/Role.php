<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [

        'name',

        'description',

        'status'

    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * The default system role a new hire lands in, based on their
     * employment type. Faculty-type employment gets "Employee - Faculty",
     * everything else gets "Employee - Non Acad" — the roles table split
     * the old single "Employee" role into these two.
     */
    public static function defaultForEmploymentType(EmploymentType $employmentType)
    {
        $roleName = str_contains($employmentType->name, 'Faculty')
            ? 'Employee - Faculty'
            : 'Employee - Non Acad';

        return self::where('name', $roleName)->first();
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function materials()
    {
        return $this->hasMany(Material::class);

        
    }

    public function create()
    {
        $materials = Material::with('category')->get();

        $departments = Department::all();

        $categories = Category::all();

        return view(
            'material_request.form',
            compact(
                'materials',
                'departments',
                'categories'
            )
        );
    }
}
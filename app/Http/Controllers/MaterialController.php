<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with(['category', 'unit'])->get();

        return view('materials.index', compact('materials'));

        
    }
}
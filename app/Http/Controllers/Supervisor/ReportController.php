<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view('supervisor.reports.index');
    }

    public function inventory()
    {
        return view('supervisor.reports.inventory');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\bulan;
use Illuminate\Http\Request;

class bulanController extends Controller
{
    public function index()
    {
        $months = bulan::all();
        return view('months.index', compact('months'));
    }
}

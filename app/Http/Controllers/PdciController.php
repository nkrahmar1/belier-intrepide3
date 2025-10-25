<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdciController extends Controller
{
    public function index()
    {
        return view('home.pdcirda');
    }
}

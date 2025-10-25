<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestArticleController extends Controller
{
    public function index()
    {
        return 'Test du nouveau contrôleur des articles';
    }
}

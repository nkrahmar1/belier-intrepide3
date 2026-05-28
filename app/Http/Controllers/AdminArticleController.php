<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;

class AdminArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $articles = \App\Models\Article::latest()->paginate(10);
        if ($request->ajax()) {
            return view('admin.articles-content', compact('articles'));
        }
        return view('admin.articles', compact('articles'));
    }
}

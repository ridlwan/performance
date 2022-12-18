<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        return Inertia::render('Document/Index');
    }
    
    public function create()
    {
        return Inertia::render('Document/Create');
    }

    public function edit()
    {
        return Inertia::render('Document/Edit');
    }
}

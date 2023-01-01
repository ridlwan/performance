<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('view-dashboard')) {
            return redirect('/attendance');
        }

        return Inertia::render('Dashboard/Index', [
            'title' => 'Dashboard'
        ]);
    }
}

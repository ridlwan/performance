<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('view-dashboard')) {
            return redirect('/attendance');
        }

        $working = User::where('status', User::STATUS_WORKING)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $outOfOffice = User::where('status', User::STATUS_OUT_OF_OFFICE)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $outSick = User::where('status', User::STATUS_OUT_SICK)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $notAvailable = User::where('status', User::STATUS_NOT_AVAILABLE)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $activities = Activity::with('attendance.user')
            ->whereDate('created_at', Carbon::now())
            ->orderByDesc('id')->get();

        return Inertia::render('Dashboard/Index', [
            'working' => $working,
            'outOfOffice' => $outOfOffice,
            'outSick' => $outSick,
            'notAvailable' => $notAvailable,
            'activities' => $activities
        ]);
    }
}

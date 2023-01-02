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

        $workingRemotely = User::where('status', User::STATUS_WORKING_REMOTELY)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $workingOnsite = User::where('status', User::STATUS_WORKING_ONSITE)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $outOfOffice = User::where('status', User::STATUS_OUT_OF_OFFICE)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $outSick = User::where('status', User::STATUS_OUT_SICK)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $notAvailable = User::where('status', User::STATUS_NOT_AVAILABLE)
            ->where('teammate', User::TEAMMATE_YES)->get();

        $activities = Activity::with('attendance.user')
            ->whereDate('created_at', Carbon::now())
            ->whereHas('attendance.user', function ($query) {
                $query->where('teammate', User::TEAMMATE_YES);
            })
            ->orderByDesc('id')->get();

        return Inertia::render('Dashboard/Index', [
            'workingRemotely' => $workingRemotely,
            'workingOnsite' => $workingOnsite,
            'outOfOffice' => $outOfOffice,
            'outSick' => $outSick,
            'notAvailable' => $notAvailable,
            'activities' => $activities
        ]);
    }
}

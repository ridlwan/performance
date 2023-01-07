<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Activity;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Events\StatusEvent;
use Illuminate\Http\Request;
use App\Events\ActivityEvent;
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
    
    public function activity(Request $request)
    {
        if ($attendance = Attendance::where('user_id', User::where('name', $request->user)->first()->id)->whereDate('created_at', Carbon::parse($request->date))->first()) {
            if ($attendance->activities->count() > 0) {
                return $attendance->activities;
            } else {
                return $attendance->status_text;
            }
        }

        return 'Not Available';
    }

    public function daily(Request $request)
    {
        $search = $request->get('search');
        $user = $request->get('user');

        if ($request->get('user')) {
            $user = $request->get('user');
        } else {
            $user = 'All';
        }

        if ($request->get('startDate')) {
            $startDate = $request->get('startDate');
        } else {
            $startDate = Carbon::now();
        }

        if ($request->get('endDate')) {
            $endDate = $request->get('endDate');
        } else {
            $endDate = Carbon::now();
        }

        if ($request->get('paginate')) {
            $paginate = $request->get('paginate');
        } else {
            $paginate = 10;
        }

        $filters = $request->only(['search']);
        $filters['startDate'] = $startDate;
        $filters['endDate'] = $endDate;
        $filters['user'] = $user;
        $filters['paginate'] = $paginate;

        $attendances = Attendance::with('activities', 'user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            })
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->whereHas('user', function ($query) use ($user) {
                $query->where('teammate', User::TEAMMATE_YES)
                    ->when($user != 'All', function ($query) use ($user) {
                        $query->where('id', $user);
                    });
            })
            ->orderByDesc('id')->paginate($paginate);

        $queryString = [
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'user' => $user,
            'paginate' => $paginate,
        ];

        $attendances->appends($queryString);

        $users = User::where('teammate', User::TEAMMATE_YES)->get();

        $userSeries = User::where('teammate', User::TEAMMATE_YES)
            ->when($user != 'All', function ($query) use ($user) {
                $query->where('id', $user);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })->get();

        $series = [];
        foreach ($userSeries as $user) {
            $data = [
                'name' => $user->name,
                'data' => []
            ];
            array_push($series, $data);
        }

        $dates = [];
        if ($startDate == $endDate || $startDate > $endDate || $attendances->count() < 1) {
            $showChart = false;
        } else {
            $showChart = true;

            $range = CarbonPeriod::create($startDate, $endDate);
            foreach ($range as $period) {
                array_push($dates, $period->format('D d M y'));

                foreach ($series as $key => $value) {
                    if ($attendance = Attendance::where('user_id', $userSeries[$key]->id)->whereDate('created_at', $period)->first()) {
                        array_push($series[$key]['data'], number_format((float) ($attendance->duration / 60), 1));
                    } else {
                        array_push($series[$key]['data'], 0);
                    }
                }
            }
        }

        return Inertia::render('Daily/Index', [
            'attendances' => $attendances,
            'users' => $users,
            'filters' => $filters,
            'showChart' => $showChart,
            'series' => $series,
            'dates' => $dates
        ]);
    }

    public function monthly(Request $request)
    {
        $activities = Activity::with('attendance.user')
            ->whereDate('created_at', Carbon::now())
            ->whereHas('attendance.user', function ($query) {
                $query->where('teammate', User::TEAMMATE_YES);
            })
            ->orderByDesc('id')->get();

        return Inertia::render('Dashboard/Index', [
            'activities' => $activities
        ]);
    }

    public function pusher()
    {
        event(new ActivityEvent('New notification'));
        event(new StatusEvent('New status'));
        return 'ok';
    }
}

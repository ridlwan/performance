<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Activity;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index()
    {
        $quote = Inspiring::quote();

        $relogin = $this->relogin();
        $activities = [];

        if (Auth::user()->attendances->count() > 0) {
            $activities = Auth::user()->attendances->last()->activities;
        }

        return Inertia::render('Attendance/Index', [
            'quote' => $quote,
            'activities' => $activities,
            'relogin' => $relogin
        ]);
    }

    public function history()
    {
        $attendances = Attendance::with('activities')
            ->where('user_id', Auth::user()->id)
            ->orderByDesc('id')->paginate(10);

        return Inertia::render('Attendance/History', [
            'attendances' => $attendances
        ]);
    }

    public function performance()
    {
        $thisYear = Carbon::now()->format('Y');
        $thisMonth = Carbon::now()->format('m');

        $attendances = Attendance::where('user_id', Auth::user()->id)
            ->whereYear('created_at', $thisYear)
            ->whereMonth('created_at', $thisMonth)->get();

        $hours = [];
        $dates = [];

        foreach ($attendances as $attendance) {
            array_push($hours, number_format((float) ($attendance->duration / 60), 1));
            array_push($dates, $attendance->created_at->format('d M'));
        }

        return Inertia::render('Attendance/Performance', [
            'hours' => $hours,
            'dates' => $dates
        ]);
    }

    public function checkIn()
    {
        DB::beginTransaction();

        $relogin = $this->relogin();

        if ($relogin) {
            $lastAttendance = Auth::user()->attendances->last();
            $lastAttendance->relogin = Carbon::now();
            $lastAttendance->save();
        } else {
            Attendance::create([
                'user_id' => Auth::user()->id,
                'start' => Carbon::now()
            ]);
        }

        Auth::user()->status = User::STATUS_WORKING;
        Auth::user()->save();

        DB::commit();

        return redirect()->back();
    }

    public function relogin()
    {
        $relogin = false;

        if (Auth::user()->attendances->count() > 0 && Auth::user()->attendances->last()->end) {
            if (Auth::user()->attendances->last()->created_at->isSameDay(Carbon::now())) {
                $relogin = true;
            }
        }

        return $relogin;
    }

    public function struggle($id)
    {
        if ($activity = Activity::find($id)) {
            $activity->struggle = Activity::STRUGGLE_YES;
            $activity->save();
        }

        return redirect()->back();
    }

    public function addActivity(Request $request)
    {
        DB::beginTransaction();

        $relogin = $this->relogin();

        if (Auth::user()->attendances->last()->activities->count() < 1 || $relogin) {
            $request->validate([
                'description' => 'required'
            ]);

            $firstActivity = true;
        } else {
            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'start' => 'required',
            ]);

            $validator->after(function ($validator) use ($request) {
                if ($request->start) {
                    if (Carbon::now()->setTimeFromTimeString($request->start)->gt(Carbon::now())) {
                        $validator->errors()->add(
                            'start',
                            'start time greater than now'
                        );
                    }

                    if (Carbon::now()->setTimeFromTimeString($request->start)->lte(Auth::user()->attendances->last()->activities->last()->start)) {
                        $validator->errors()->add(
                            'start',
                            'start time less than last start activity'
                        );
                    }
                }
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $firstActivity = false;
        }

        $data = $request->all();
        $data['attendance_id'] = Auth::user()->attendances->last()->id;

        if ($firstActivity) {
            if ($relogin) {
                $data['start'] = Auth::user()->attendances->last()->relogin;
            } else {
                $data['start'] = Auth::user()->attendances->last()->start;
            }
        } else {
            $data['start'] = Carbon::now()->setTimeFromTimeString($request->start);

            $lastActivity = Auth::user()->attendances->last()->activities->last();
            $lastActivity->end = Carbon::now()->setTimeFromTimeString($request->start);

            $duration = $lastActivity->start->diffInMinutes(Carbon::now()->setTimeFromTimeString($request->start));

            if ($duration > 0) {
                $lastActivity->duration = $duration;
            } else {
                $lastActivity->duration = 1;
            }

            $lastActivity->save();
        }

        Activity::create($data);

        if ($relogin) {
            $lastAttendance = Auth::user()->attendances->last();
            $lastAttendance->end = null;
            $lastAttendance->relogin = null;
            $lastAttendance->duration = null;
            $lastAttendance->save();
        }

        DB::commit();

        return redirect()->back();
    }

    public function checkOut()
    {
        DB::beginTransaction();

        $lastAttendance = Auth::user()->attendances->last();
        $lastAttendance->end = Carbon::now();

        $lastActivity = $lastAttendance->activities->last();
        $lastActivity->end = Carbon::now();

        $activityDuration = $lastActivity->start->diffInMinutes(Carbon::now());

        if ($activityDuration > 0) {
            $lastActivity->duration = $activityDuration;
        } else {
            $lastActivity->duration = 1;
        }

        $lastActivity->save();

        $attendanceDuration = Auth::user()->attendances->last()->activities->sum('duration');

        if ($attendanceDuration > 0) {
            $lastAttendance->duration = $attendanceDuration;
        } else {
            $lastAttendance->duration = 1;
        }

        $lastAttendance->save();

        Auth::user()->status = User::STATUS_NOT_AVAILABLE;
        Auth::user()->save();

        DB::commit();

        return redirect()->back();
    }

    public function outOfOffice()
    {
        Attendance::create([
            'user_id' => Auth::user()->id,
            'status' => Attendance::STATUS_OUT_OF_OFFICE
        ]);

        Auth::user()->status = User::STATUS_OUT_OF_OFFICE;
        Auth::user()->save();

        return redirect()->back();
    }

    public function outSick()
    {
        Attendance::create([
            'user_id' => Auth::user()->id,
            'status' => Attendance::STATUS_OUT_SICK
        ]);

        Auth::user()->status = User::STATUS_OUT_SICK;
        Auth::user()->save();

        return redirect()->back();
    }
}

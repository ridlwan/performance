<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Activity;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index()
    {
        $quote = Inspiring::quote();

        $relogin = false;
        $activities = [];

        if (Auth::user()->attendances->count() > 0 && Auth::user()->attendances->last()->end) {
            if (Auth::user()->attendances->last()->end->diffInDays(Carbon::now()) < 1) {
                $relogin = true;
            }
        }

        if (Auth::user()->attendances->count() > 0) {
            $activities = Auth::user()->attendances->last()->activities;
        }

        return Inertia::render('Attendance/Index', [
            'quote' => $quote,
            'activities' => $activities,
            'relogin' => $relogin
        ]);
    }

    public function checkIn()
    {
        Attendance::create([
            'user_id' => Auth::user()->id,
            'start' => Carbon::now()
        ]);

        Auth::user()->status = User::STATUS_ACTIVE;
        Auth::user()->save();

        return redirect()->back();
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
        if (Auth::user()->attendances->last()->activities->count() > 0) {
            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'start' => 'required',
            ]);
    
            $validator->after(function ($validator) use ($request) {
                if ($request->start) {
                    if (Carbon::now()->setTimeFromTimeString($request->start)->gt(Carbon::now())) {
                        $validator->errors()->add(
                            'start', 'start time greater than now'
                        );
                    }
        
                    if (Carbon::now()->setTimeFromTimeString($request->start)->lte(Auth::user()->attendances->last()->activities->last()->start)) {
                        $validator->errors()->add(
                            'start', 'start time less than last start activity'
                        );
                    }
                }
            });
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $firstActivity = false;
        } else {
            $request->validate([
                'description' => 'required'
            ]);

            $firstActivity = true;
        }
        

        $data = $request->all();
        $data['attendance_id'] = Auth::user()->attendances->last()->id;
        
        if ($firstActivity) {
            $data['start'] = Auth::user()->attendances->last()->start;
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
        
        return redirect()->back();
    }

    public function checkOut()
    {
        $lastAttendance = Auth::user()->attendances->last();
        $lastAttendance->end = Carbon::now();
        
        $attendanceDuration = $lastAttendance->start->diffInMinutes(Carbon::now());
        
        if ($attendanceDuration > 0) {
            $lastAttendance->duration = $attendanceDuration;
        } else {
            $lastAttendance->duration = 1;
        }
        
        $lastAttendance->save();

        $lastActivity = $lastAttendance->activities->last();
        $lastActivity->end = Carbon::now();

        $activityDuration = $lastActivity->start->diffInMinutes(Carbon::now());
            
        if ($activityDuration > 0) {
            $lastActivity->duration = $activityDuration;
        } else {
            $lastActivity->duration = 1;
        }

        $lastActivity->save();

        Auth::user()->status = User::STATUS_INACTIVE;
        Auth::user()->save();

        return redirect()->back();
    }

    public function outOfOffice()
    {
        Attendance::create([
            'users_id' => Auth::user()->id,
            'start' => Carbon::now(),
            'status' => Attendance::STATUS_OUT_OF_OFFICE
        ]);

        return redirect()->back();
    }

    public function outSick()
    {
        Attendance::create([
            'users_id' => Auth::user()->id,
            'start' => Carbon::now(),
            'status' => Attendance::STATUS_OUT_SICK
        ]);

        return redirect()->back();
    }
}

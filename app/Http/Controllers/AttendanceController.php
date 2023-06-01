<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Report;
use App\Models\Project;
use App\Models\Activity;
use App\Models\Attendance;
use App\Events\StatusEvent;
use Illuminate\Http\Request;
use App\Events\ActivityEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index()
    {
        $quote = Inspiring::quote();

        $relogin = $this->isRelogin();
        $activities = [];
        
        if (Auth::user()->attendances->count() > 0) {
            $activities = Auth::user()->attendances->last()->activities->load('project');
        }

        $projects = Project::where('status', Project::STATUS_OPEN)
            ->whereHas('assignments', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->get();
        
        return Inertia::render('Attendance/Index', [
            'quote' => $quote,
            'activities' => $activities,
            'relogin' => $relogin,
            'projects' => $projects
        ]);
    }
    
    public function assignment()
    {
        $projects = Project::where('status', Project::STATUS_OPEN)
            ->whereHas('assignments', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->get();
        
        return $projects;
    }
    
    public function workingUser()
    {
        $workingUsers = User::where('teammate', User::TEAMMATE_YES)
                ->where(function ($query) {
                    $query->where('status', User::STATUS_WORKING_REMOTE)
                    ->orWhere('status', User::STATUS_WORKING_ONSITE);
                })
                ->orderByDesc('updated_at')
                ->get();

        return $workingUsers;
    }

    public function history()
    {
        $attendances = Attendance::with('activities.project')
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
            array_push($dates, $attendance->created_at->format('D d M'));
        }

        if ($report = Report::whereYear('start', $thisYear)->whereMonth('start', $thisMonth)->first()) {
            if ($report->mandays > 0) {
                $mandays = $report->mandays * 8;
            } else {
                $mandays = 22 * 8;
            }
        } else {
            $mandays = 22 * 8;
        }

        $progressHours =  floor($attendances->sum('duration') / 60);
        $progressPercentage = floor((($attendances->sum('duration') / 60) / $mandays) * 100);

        return Inertia::render('Attendance/Performance', [
            'hours' => $hours,
            'dates' => $dates,
            'mandays' => $mandays,
            'progressHours' => $progressHours,
            'progressPercentage' => $progressPercentage,
        ]);
    }
    
    public function userStatus()
    {
        return Auth::user()->status_text;
    }

    public function checkIn(Request $request)
    {
        DB::beginTransaction();

        $relogin = $this->isRelogin();

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

        if ($request->position == 'remote') {
            Auth::user()->status = User::STATUS_WORKING_REMOTE;
        } else {
            Auth::user()->status = User::STATUS_WORKING_ONSITE;
        }
        
        Auth::user()->save();

        event(new StatusEvent(Auth::user()->name, 'just checked in', 'checkIn'));

        DB::commit();

        return redirect()->back();
    }

    public function isRelogin()
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

            event(new ActivityEvent(Auth::user()->name, 'is struggling in current activity', 'struggling'));
        }

        return redirect()->back();
    }

    public function addActivity(Request $request)
    {
        if ($this->isAvailable()) {
            DB::beginTransaction();
    
            $relogin = $this->isRelogin();
    
            if (Auth::user()->attendances->last()->activities->count() < 1 || $relogin) {
                $request->validate([
                    'description' => 'required',
                    'project_id' => 'nullable'
                ]);
    
                $firstActivity = true;
            } else {
                $validator = Validator::make($request->all(), [
                    'description' => 'required',
                    'project_id' => 'nullable',
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

            if ($request->project_id == 'null') {
                $data['project_id'] = null;
            } else {
                $data['project_id'] = $request->project_id;
            }
    
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

            event(new ActivityEvent(Auth::user()->name, 'just added a new activity', 'add'));
    
            DB::commit();
        }

        return redirect()->back();
    }
    
    public function updateActivity(Request $request, $id)
    {
        if ($activity = Activity::find($id)) {
            $request->validate([
                'description_updated' => 'required',
                'project_id_updated' => 'nullable',
            ]);

            $activity->description = $request->description_updated;

            if ($request->project_id_updated == 'null') {
                $activity->project_id = null;
            } else {
                $activity->project_id = $request->project_id_updated;
            }

            $activity->save();

            event(new ActivityEvent(Auth::user()->name, 'just updated the activity', 'update'));
        }

        return redirect()->back();
    }

    public function isAvailable()
    {
        $available = false;

        $relogin = $this->isRelogin();

        if ((Auth::user()->status == User::STATUS_WORKING_REMOTE || Auth::user()->status == User::STATUS_WORKING_ONSITE) || ((Auth::user()->status == User::STATUS_WORKING_REMOTE || Auth::user()->status == User::STATUS_WORKING_ONSITE) && $relogin)) {
            $available = true;
        }

        return $available;
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

        event(new StatusEvent(Auth::user()->name, 'just checked out', 'checkOut'));

        DB::commit();

        return redirect()->back();
    }

    public function outOffice()
    {
        Attendance::create([
            'user_id' => Auth::user()->id,
            'status' => Attendance::STATUS_OUT_OFFICE
        ]);

        Auth::user()->status = User::STATUS_OUT_OFFICE;
        Auth::user()->save();

        event(new StatusEvent(Auth::user()->name, 'is marked out off office', 'outOffice'));
        
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
        
        event(new StatusEvent(Auth::user()->name, 'is marked out sick', 'outSick'));

        return redirect()->back();
    }
}

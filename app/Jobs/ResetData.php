<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Activity;
use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ResetData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (User::get() as $user) {
            $user->status = User::STATUS_NOT_AVAILABLE;
            $user->save();
        }

        $activities = Activity::whereNull('end')->get();
        foreach ($activities as $activity) {
            if ($activity->start->diffInMinutes(Carbon::now()) > 60) {
                $activity->end = $activity->start->addMinutes(60);
                $activity->duration = 60;
                $activity->save();
            } else {
                $activity->end = Carbon::now();
                $activity->duration = $activity->start->diffInMinutes(Carbon::now());
                $activity->save();
            }

            $attendance = $activity->attendance;
            $attendance->end = $activity->end;
            $attendance->duration = $attendance->activities->sum('duration');
            $attendance->save();
        }

        $reloginAttendances = Attendance::whereNotNull('relogin')->get();
        foreach ($reloginAttendances as $reloginAttendance) {
            $reloginAttendance->relogin = null;
            $reloginAttendance->save();
        }
    }
}

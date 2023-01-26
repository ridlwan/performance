<?php

namespace App\Exports\Sheets;

use App\Models\Activity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ActivitiesPerMonthSheet implements FromView, WithTitle
{
    private $user;
    private $start;
    private $end;

    public function __construct($user, $start, $end)
    {
        $this->user = $user;
        $this->start = $start;
        $this->end  = $end;
    }

    public function view(): View
    {
        $activities = Activity::with('project')
            ->whereHas('attendance', function ($query) {
                $query->where('user_id', $this->user->id);
            })
            ->whereDate('created_at', '>=', $this->start)
            ->whereDate('created_at', '<=', $this->end)->get();

        return view('exports.activities', compact('activities'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->user->name;
    }
}
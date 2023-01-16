<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\Sheets\ActivitiesPerMonthSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ActivitiesExport implements WithMultipleSheets
{
    use Exportable;

    protected $start;
    protected $end;
    
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $users = User::whereHas('attendances', function ($query) {
                $query->whereDate('created_at', '>=', $this->start)
                    ->whereDate('created_at', '<=', $this->end);
            })
            ->where('reported', User::REPORTED_YES)
            ->where('teammate', User::TEAMMATE_YES)
            ->orderBy('order')->get();

        $sheets = [];

        foreach ($users as $user) {
            $sheets[] = new ActivitiesPerMonthSheet($user, $this->start, $this->end);
        }

        return $sheets;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    const STRUGGLE_NO = 0;
    const STRUGGLE_YES = 1;

    const STRUGGLE_ARRAY = [
        self::STRUGGLE_NO => 'No',
        self::STRUGGLE_YES => 'Yes',
    ];

    protected $fillable = [
        'attendance_id',
        'description',
        'project_id',
        'start',
        'end',
        'duration',
        'struggle'
    ];

    protected $dates = [
        'start',
        'end'
    ];

    protected $appends = [
        'start_time',
        'struggle_text'
    ];

    public function getStartTimeAttribute() {
        return $this->start->format('H:i');;
    }

    public function getStruggleTextAttribute() {
        return self::STRUGGLE_ARRAY[$this->struggle];
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

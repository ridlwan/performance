<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    const STATUS_WORKING = 0;
    const STATUS_OUT_OF_OFFICE = 1;
    const STATUS_OUT_SICK = 2;

    const STATUS_ARRAY = [
        self::STATUS_WORKING => 'Working',
        self::STATUS_OUT_OF_OFFICE => 'Out of Office',
        self::STATUS_OUT_SICK => 'Out Sick',
    ];

    protected $fillable = [
        'user_id',
        'start',
        'end',
        'relogin',
        'duration',
        'status'
    ];

    protected $dates = [
        'start',
        'end',
        'relogin'
    ];

    protected $appends = [
        'status_text'
    ];

    public function getStatusTextAttribute() {
        return self::STATUS_ARRAY[$this->status];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    const STATUS_NOT_AVAILABLE = 0;
    const STATUS_WORKING_REMOTE = 1;
    const STATUS_WORKING_ONSITE = 2;
    const STATUS_OUT_OF_OFFICE = 3;
    const STATUS_OUT_SICK = 4;

    const STATUS_ARRAY = [
        self::STATUS_NOT_AVAILABLE => 'Not Available',
        self::STATUS_WORKING_REMOTE => 'Working Remote',
        self::STATUS_WORKING_ONSITE => 'Working Onsite',
        self::STATUS_OUT_OF_OFFICE => 'Out of Office',
        self::STATUS_OUT_SICK => 'Out Sick',
    ];
    
    const REPORTED_NO = 0;
    const REPORTED_YES = 1;

    const REPORTED_ARRAY = [
        self::REPORTED_NO => 'No',
        self::REPORTED_YES => 'Yes',
    ];
    
    const TEAMMATE_NO = 0;
    const TEAMMATE_YES = 1;

    const TEAMMATE_ARRAY = [
        self::TEAMMATE_NO => 'No',
        self::TEAMMATE_YES => 'Yes',
    ];
    
    const DARKMODE_NO = 0;
    const DARKMODE_YES = 1;

    const DARKMODE_ARRAY = [
        self::DARKMODE_NO => 'No',
        self::DARKMODE_YES => 'Yes',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'position',
        'password',
        'avatar',
        'background',
        'dark_mode',
        'status',
        'reported',
        'teammate',
        'resigned_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'status_text',
        'reported_text',
        'teammate_text',
        'dark_mode_text'
    ];

    protected $dates = [
        'resigned_at'
    ];

    public function getStatusTextAttribute() {
        return self::STATUS_ARRAY[$this->status];
    }
    
    public function getReportedTextAttribute() {
        return self::REPORTED_ARRAY[$this->reported];
    }

    public function getTeammateTextAttribute() {
        return self::TEAMMATE_ARRAY[$this->teammate];
    }
    
    public function getDarkModeTextAttribute() {
        return self::DARKMODE_ARRAY[$this->dark_mode];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

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
    const STATUS_WORKING = 1;
    const STATUS_OUT_OF_OFFICE = 2;
    const STATUS_OUT_SICK = 3;

    const STATUS_ARRAY = [
        self::STATUS_NOT_AVAILABLE => 'Not Available',
        self::STATUS_WORKING => 'Working',
        self::STATUS_OUT_OF_OFFICE => 'Out of Office',
        self::STATUS_OUT_SICK => 'Out Sick',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'background',
        'status',
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
        'status_text'
    ];

    public function getStatusTextAttribute() {
        return self::STATUS_ARRAY[$this->status];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    const TYPE_PROJECT = 0;
    const TYPE_MANAGE_SERVICE = 1;

    const TYPE_ARRAY = [
        self::TYPE_PROJECT => 'Project',
        self::TYPE_MANAGE_SERVICE => 'Manage Service',
    ];
    
    const STATUS_OPEN = 0;
    const STATUS_CLOSE = 1;

    const STATUS_ARRAY = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_CLOSE => 'Close',
    ];

    protected $fillable = [
        'name',
        'type',
        'status'
    ];

    protected $appends = [
        'type_text',
        'status_text'
    ];

    public function getTypeTextAttribute() {
        return self::TYPE_ARRAY[$this->type];
    }

    public function getStatusTextAttribute() {
        return self::STATUS_ARRAY[$this->status];
    }

    public function progresses()
    {
        return $this->hasMany(Progress::class);
    }
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    const STATUS_OPEN = 0;
    const STATUS_CLOSE = 1;

    const STATUS_ARRAY = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_CLOSE => 'Close',
    ];

    protected $fillable = [
        'name',
        'status'
    ];

    protected $appends = [
        'status_text'
    ];

    public function getStatusTextAttribute() {
        return self::STATUS_ARRAY[$this->status];
    }
}

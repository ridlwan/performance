<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'closed',
        'completed',
        'waiting',
        'sla'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}

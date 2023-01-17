<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'waiting_for_support',
        'waiting_for_customer',
        'waiting_for_partner',
        'escalated',
        'pending',
        'in_progress',
        'resolved',
        'completed',
        'closed',
        'canceled',
        'sla'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}

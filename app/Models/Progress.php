<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'project_id',
        'jira',
        'development',
        'testing',
        'overall',
        'sla'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

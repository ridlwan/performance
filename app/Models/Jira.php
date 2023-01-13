<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jira extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'project_id',
        'value'
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
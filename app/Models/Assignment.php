<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'responsibility_id',
        'project_id'
    ];

    public function responsibility()
    {
        return $this->belongsTo(Responsibility::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

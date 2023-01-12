<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    const PUBLISHED_NO = 0;
    const PUBLISHED_YES = 1;

    const PUBLISHED_ARRAY = [
        self::PUBLISHED_NO => 'No',
        self::PUBLISHED_YES => 'Yes',
    ];

    protected $fillable = [
        'name',
        'start',
        'end',
        'published'
    ];

    protected $dates = [
        'start',
        'end'
    ];

    protected $appends = [
        'published_text'
    ];

    public function getPublishedTextAttribute() {
        return self::PUBLISHED_ARRAY[$this->published];
    }
}

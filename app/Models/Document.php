<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_nopeg',
        'sender_name',
        'sender_unit',
        'sender_date',
        'receiver_nopeg',
        'receiver_name',
        'receiver_unit',
        'receiver_date',
        'created_by'
    ];

    public function descriptions()
    {
        return $this->hasMany(Description::class);
    }
}

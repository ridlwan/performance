<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'quantity',
        'description',
        'remark',
        'created_by',
        'updated_by'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

<?php

namespace App\Models;

use App\Models\Manual;
use Illuminate\Database\Eloquent\Model;

class ManualFile extends Model
{
    protected $fillable = [
        'manual_id',
        'file_path',
        'file_type',
    ];

    public function manual()
    {
        return $this->belongsTo(Manual::class);
    }
}

<?php

namespace App\Models;

use App\Models\Manual;
use Illuminate\Database\Eloquent\Model;

class AiAnalysis extends Model
{
    protected $fillable = [
        'manual_id',
        'role',
        'content',
    ];
    
    public function manual()
    {
        return $this->belongsTo(Manual::class);
    }
}

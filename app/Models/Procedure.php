<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = ['classification_id', 'name', 'description'];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    public function manual()
    {
        return $this->hasOne(Manual::class, 'procedure_id');
    }
}

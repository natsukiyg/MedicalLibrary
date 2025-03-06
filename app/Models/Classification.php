<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    protected $fillable = ['specialty_id', 'name', 'description'];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function procedures()
    {
        return $this->hasMany(Procedure::class);
    }

    public function manuals()
    {
        return $this->hasMany(Manual::class);
    }
}

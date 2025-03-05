<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $fillable = ['department_id', 'name'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function manuals()
    {
        return $this->hasMany(Manual::class);
    }

    public function knowledges()
    {
        return $this->hasMany(Knowledge::class);
    }
}

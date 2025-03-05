<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'name',
        'address',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
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

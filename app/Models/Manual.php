<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    protected $fillable = [
        'title', 'content', 'hospital_id', 'department_id',
        'specialty_id', 'version', 'updated_by'
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
    
    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }    

    public function creater()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

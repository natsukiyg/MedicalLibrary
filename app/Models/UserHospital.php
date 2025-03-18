<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHospital extends Model
{
    protected $table = 'user_hospital';
    protected $fillable = ['user_id', 'hospital_id', 'department_id', 'specialty_id', 'role', 'approval_status', 'rejection_reason'];

    // role属性を整数にキャストする
    protected $casts = [
        'role' => 'integer',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
}

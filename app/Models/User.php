<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * ユーザーが特定のロールを持っているかどうかを確認するメソッド。
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        // ユーザーのロールを確認するロジックを実装
        // ここでは、ユーザーの role 属性がロールマッピングのキーと一致するかを確認します
        $roleMapping = [
            'staff'       => 0,
            'team_member' => 1,
            'admin'       => 2,
            'operator'    => 3,
        ];

        return $this->role === ($roleMapping[$role] ?? null);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'user_hospital');
    }

    public function userHospital()
    {
        return $this->hasOne(UserHospital::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }
}

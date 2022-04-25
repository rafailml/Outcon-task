<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function roleName()
    {
        return $this->role->name;
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'employee_managers', 'employee_id', 'manager_id');
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_managers', 'manager_id', 'employee_id');
    }

    public function userIsEmployee(User $user)
    {
        return $this->employees()->where('employee_id', $user->id)->count();
    }
}

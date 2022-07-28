<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DateTimeInterface;
use Auth;
use Session;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $connection = 'sqlsrv';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'username', 'level', 'avatar', 'IsDelete'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function role()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role');
    }

    public function checkRole($role)
    {
        if (Auth::check()) {
            if (Auth::user()->level == 9999) return true;
        }
        if (Session::has('roles')) {
            $find = array_search($role, Session::get('roles'));
            if ($find !== false) return true;
        } else {
            if ($this->role()->where('role', $role)->count() == 1) {
                return true;
            }
        }

        return false;
    }
}

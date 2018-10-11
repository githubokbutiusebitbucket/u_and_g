<?php

namespace App;

use App\Group;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'last_name', 'first_name', 'state'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'updated_at', 'pivot'
    ];

    /**
     * Stack attributes
     *
     * @var array
     */
    protected $casts = [
        'state' => 'boolean',
    ];

    /**
     * [groups description]
     * @return [type] [description]
     */
    public function groups(){
        return $this->belongsToMany(Group::class, 'users_groups', 'user_id', 'group_id');
    }

    /**
     * [setPasswordAttribute description]
     * @param [type] $password [description]
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

}

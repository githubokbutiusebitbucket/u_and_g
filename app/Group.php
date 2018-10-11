<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at', 'pivot'
    ];

    /**
     * [users description]
     * @return [type] [description]
     */
    public function users(){
        return $this->belongsToMany(User::class, 'users_groups', 'group_id', 'user_id');
    }

}

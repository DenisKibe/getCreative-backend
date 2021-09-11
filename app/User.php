<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','userId','user_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get all the articles for the user.
     */
    public function articles()
    {
        return $this->hasMany(Articles::class,'user_id','userId');
    }

    /**
     * Get all the comments for a user
     */
    public function comments()
    {
        return $this->hasMany(Comments::class,'user_id','userId');
    }

    /**
     * Get all the likes for a user
     */
    public function likes()
    {
        return $this->hasMany(Likes::class,'user_id','userId');
    }

    /**
     * Get all the followers for this user
     */
    public function followers()
    {
        return $this->hasMany(Followers::class,'user_id','userId');
    }

    /**
     * get all details for a user
     */
    public function userExtraDetails()
    {
        return $this->hasMany(UserExtraDetails::class,'user_id','userId');
    }

    //to generate the token
   public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
    /**
     * arttributes that are mass fillable
     */
    protected $fillable=['follow_id','followed','follow'];

    /**
     * get user that owns the follow
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExtraDetails extends Model
{
    /**
     * fields that are mass assignable
     */
    protected $fillable=['details_id','profile_picture'];

    /**
     * Get which user the details belong to
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','userId');
    }
}

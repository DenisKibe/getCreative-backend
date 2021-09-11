<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    /**
     * attributes that are mass fillable
     */
    protected $fillable=['comment_identifier','comment','article_id'];

    /**
     * get user that own the comment
     */
    public function user()
    {
        return $this->belongsto(User::class,'user_id','userId');
    }

    /**
     * Get artictles that own the comment
     */
    public function articles()
    {
        return $this->belongsto(Articles::class,'article_id','blog_identifier');
    }
}

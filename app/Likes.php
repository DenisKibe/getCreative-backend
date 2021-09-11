<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    /**
     * attributes that are mass fillable
     */
    protected $fillable=['like_identifier','likes','article_id'];

    /**
     * Get user that owns the like
     */
    public function user()
    {
        return $this->belongsto(User::class,'user_id','userId');
    }

    /**
     * Get articles that owns the like
     */
    public function articles()
    {
        return $this->belongsto(Article::class,'article_id','blog_identifier');
    }
}

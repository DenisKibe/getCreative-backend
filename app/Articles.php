<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articles extends Model
{
    use SoftDeletes;
    /**
     * attributes that should be mutated to dates;
     */
    protected $dates=['deleted_at'];
    /**
     * attributes that are mass fillable
     */
    protected $fillable=['blog_identifier','title','text_path','category_id'];
    /**
     * get the user that owns the article
     */
    public function user()
    {
       return $this->belongsto(User::class,'userId','user_id');
    }

    /**
     * get all comments for an article
     */
    public function comments()
    {
        return $this->hasMany(Comments::class,'blog_identifier','article_id');
    }

    /**
     * get all likes for an article
     */
    public function likes()
    {
        return $this->hasMany(Likes::class,'blog_identifier','	article_id');
    }

    /**
     * an article has more
     */
    public function categorys()
    {
        return $this->belongsto(Categorys::class,'category_id'.'category_id');
    }
}

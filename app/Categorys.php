<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorys extends Model
{
    /**
     * a category has many articles
     */
    public function articles()
    {
        return $this->hasMany(Articles::class,'category_id','category_id');
    }
}

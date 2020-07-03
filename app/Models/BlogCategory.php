<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $table = 'tbl_blog_category';
    public $timestamps = false;
    public $fillable = ['title'];

    public function posts(){
        return $this->hasMany('App\Models\Blog','category_id');
    }
}

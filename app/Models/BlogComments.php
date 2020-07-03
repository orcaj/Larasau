<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComments extends Model
{
    protected $table = 'tbl_blog_comments';
    protected $fillable = ['comment','user_id','email','create_at','update_at','name','post_id','parent','mode'];
    public $timestamps = false;

    public function post(){
        return $this->belongsTo('App\Models\Blog','post_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function childs(){
        return $this->hasMany('App\Models\BlogComments','parent');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'tbl_blog_posts';
    public $fillable = ['title','pre_content','content','category_id','create_at','update_at','tags','image','user_id','comments','mode'];
    public $timestamps = false;

    public function comments(){
        return $this->hasMany('App\Models\BlogComments','post_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\BlogCategory','category_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'tbl_article';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\ContentCategory','cat_id');
    }
    public function rate(){
        return $this->hasMany('App\Models\ArticleRate','article_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategoryFilter extends Model
{
    protected $fillable = ['category_id','filter','sort'];
    protected $table = 'tbl_contents_category_filter';
    public $timestamps = false;

    public function category(){
        return $this->belongsTo('App\Models\ContentCategory','category_id');
    }

    public function tags(){
        return $this->hasMany('App\Models\ContentCategoryFilterTag','filter_id');
    }
}

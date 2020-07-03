<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategoryFilterTag extends Model
{
    public $timestamps = false;
    protected $table = 'tbl_contents_category_filter_tag';
    protected $fillable = ['filter_id','tag','sort'];

    public function filter(){
        return $this->belongsTo('App\Models\ContentCategoryFilter','filter_id');
    }

    public function contents(){
        return $this->belongsToMany('App\Models\Content','tbl_contents_category_filter_tag_relation','tag_id','content_id');
    }
}

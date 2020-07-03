<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategoryFilterTagRelation extends Model
{
    protected $table = 'tbl_contents_category_filter_tag_relation';
    public $timestamps = false;
    protected $fillable = ['content_id', 'category_id', 'filter_id', 'tag_id'];

    public function tag(){
        return $this->belongsTo('App\Models\ContentCategoryFilterTag','tag_id');
    }
}

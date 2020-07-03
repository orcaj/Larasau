<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleRate extends Model
{
    protected $table = 'tbl_article_rate';
    protected $guarded = ['id'];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'tbl_favorite';
    protected $fillable = ['content_id','user_id'];
    public $timestamps = false;
}

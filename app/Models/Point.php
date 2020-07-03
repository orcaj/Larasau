<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = 'tbl_point';
    protected $fillable = ['user_id','preferential_id','mode','rate'];
    public $timestamps = false;

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $table = 'tbl_follow';
    protected $fillable = ['follower','user_id','type'];
    public $timestamps = false;
}

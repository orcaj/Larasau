<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestFans extends Model
{
    protected $fillable = ['user_id','request_id'];
    protected $table = 'tbl_request_fans';
    public $timestamps = false;
}

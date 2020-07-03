<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'tbl_login';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'tbl_user_channel';
    protected $fillable = ['title', 'user_id', 'description', 'formal', 'mode', 'image', 'avatar', 'attach'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function contents(){
        return $this->hasMany('App\Models\ChannelVideo','chanel_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelRequest extends Model
{
    protected $table = 'tbl_user_channel_request';
    protected $fillable = ['title','create_at','user_id','channel_id','mode','attach'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function channel(){
        return $this->belongsTo('App\Models\Channel','channel_id');
    }
}

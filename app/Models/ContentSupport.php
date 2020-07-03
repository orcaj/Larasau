<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentSupport extends Model
{
    protected $table = 'tbl_contents_support';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function supporter(){
        return $this->belongsTo('App\Models\User','supporter_id');
    }

    public function sender(){
        return $this->belongsTo('App\Models\User','sender_id');
    }

    public function content(){
        return $this->belongsTo('App\Models\Content','content_id');
    }

    public function messages(){
        return $this->hasMany('App\Models\ContentSupport');
    }
}

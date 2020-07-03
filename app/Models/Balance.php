<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    public $timestamps = false;
    protected $table = 'tbl_balance_log';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function exporter(){
        return $this->belongsTo('App\Models\User','exporter_id');
    }

}

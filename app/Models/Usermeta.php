<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usermeta extends Model
{
    protected $table = 'tbl_users_meta';
    protected $fillable = ['user_id', 'option', 'value', 'mode'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public static function updateOrNew($user_id,$data){
        foreach ($data as $key=>$val){
            Usermeta::updateOrCreate(
                ['user_id'=>$user_id,'option'=>$key],
                ['value'=>$val]
            );
        }
    }
}

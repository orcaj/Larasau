<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['option','value','mode'];
    protected $table = 'tbl_option';
    public $timestamps = false;

    public static function updateOrNew($data){
        foreach ($data as $key=>$val){
            Option::updateOrCreate(
                ['option'=>$key],
                ['value'=>$val]
            );
        }
    }

}

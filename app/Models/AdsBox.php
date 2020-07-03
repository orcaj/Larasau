<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsBox extends Model
{
    protected $table = 'tbl_ads_box';
    protected $fillable = ['title','size','position','mode','image','url','sort','create_at'];
    public $timestamps = false;
}

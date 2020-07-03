<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsPlan extends Model
{
    protected $table = 'tbl_ads_plan';
    protected $fillable = ['title','description','price','day','mode'];
    public $timestamps = false;
}

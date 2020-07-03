<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usercategories extends Model
{
    protected $table = "tbl_users_category";
    public $timestamps = false;
    protected $fillable = ['title','off','commision','mode','catability','image'];

    public function users()
    {
        return $this->hasMany('App\Models\User','category_id');
    }
}

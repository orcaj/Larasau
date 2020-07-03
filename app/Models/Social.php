<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $table = 'tbl_social';
    public $timestamps = false;
    protected $fillable = ['title','link','icon','sort'];
}

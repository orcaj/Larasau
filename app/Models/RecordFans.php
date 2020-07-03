<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordFans extends Model
{
    protected $table = 'tbl_record_fans';
    protected $guarded = ['id'];
    public $timestamps = false;
}

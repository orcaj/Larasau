<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentRate extends Model
{
    protected $table = 'tbl_content_rate';
    protected $guarded = ['id'];
    public $timestamps = false;
}

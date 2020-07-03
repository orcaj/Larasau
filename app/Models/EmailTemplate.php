<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'tbl_emails_template';
    protected $guarded = ['id'];
    public $timestamps = false;
}

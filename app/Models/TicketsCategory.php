<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketsCategory extends Model
{
    protected $table = 'tbl_tickets_category';
    public $timestamps = false;

    public function tickets()
    {
        return $this->hasMany('App\Models\Tickets','category_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountContent extends Model
{
    protected $table = 'tbl_discount';
    public $timestamps = false;
    protected $fillable = ['type','mode','first_date','last_date','off','off_id','create_at'];

    public function content(){
        return $this->belongsTo('App\Models\Content','off_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\ContentCategory','off_id');
    }
}

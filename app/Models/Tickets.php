<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    protected $table = 'tbl_tickets';
    public $timestamps = false;
    protected $fillable = ['title','mode','create_at','user_id','category_id','update_at'];

    public function messages(){
        return $this->hasMany('App\Models\TicketsMsg','ticket_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\TicketsCategory','category_id');
    }
    public function users(){
        return $this->hasMany('App\Models\TicketsUser','ticket_id');
    }
}

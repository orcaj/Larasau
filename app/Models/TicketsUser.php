<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketsUser extends Model
{
    protected $table = 'tbl_tickets_user';
    protected $fillable = ['ticket_id','user_id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
}

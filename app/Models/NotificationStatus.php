<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationStatus extends Model
{
    protected $table = 'tbl_notification_status';
    protected $fillable = ['notification_id','user_id','create_at'];
    public $timestamps = false;

    public function notification(){
        return $this->belongsTo('App\Models\Notification','notification_id');
    }
}

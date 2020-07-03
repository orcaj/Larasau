<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'tbl_users';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function usermetas()
    {
        return $this->hasMany('App\Models\Usermeta');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Usercategories','category_id');
    }
    public function contents()
    {
        return $this->hasMany('App\Models\Content');
    }
    public function sells()
    {
        return $this->hasMany('App\Models\Sell');
    }
    public function buys()
    {
        return $this->hasMany('App\Models\Sell','buyer_id');
    }
    public function channels()
    {
        return $this->hasMany('App\Models\Channel','user_id');
    }
    public function point()
    {
        return $this->hasMany('App\Models\Point','user_id');
    }
    public function preferential()
    {
        return $this->hasMany('App\Models\Point','preferential_id');
    }
    public function follow(){
        return $this->hasMany('App\Models\Follower','user_id');
    }
    public function support(){
        return $this->hasMany('App\Models\ContentSupport','sender_id');
    }
    public function supporter(){
        return $this->hasMany('App\Models\ContentSupport','supporter_id');
    }

}

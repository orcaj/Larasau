<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentMeta extends Model
{
    protected $table = 'tbl_contents_meta';
    public $timestamps = false;
    public $fillable = ['content_id','option','value','mode'];

    public function content(){
        return $this->belongsTo('App\Models\Content','content_id');
    }

    public static function updateOrNew($content_id,$data){
        foreach ($data as $key => $val){
            ContentMeta::updateOrCreate(
                ['content_id'=>$content_id,'option'=>$key],
                ['value'=>$val]
            );
        }
    }
}

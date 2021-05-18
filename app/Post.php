<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /*protected $fillable = [
        'user_id',
        'user_category',
        'Photo_id',
        'title',
        'body'
    ];*/

    protected $guarded = [''];

    public function user(){
        return $this->belongsTo('App\User');
    }


    public function photo(){
        return $this->belongsTo('App\Photo', 'Photo_id'); //ya automatically id ko lower case sumjta ha islia yahan define krdiya cuz db me b yahi column banaya tha
    }

    public function category(){
        return $this->belongsTo('App\Category','user_category');
    }

    /// bus ya yad rakhna k access kase horaha ha
//<td>{{$post->user->name}}</td> ya belongsto se huwa tha
}

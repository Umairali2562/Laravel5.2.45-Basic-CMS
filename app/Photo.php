<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{


protected $fillable=['file'];
    protected $uploads='../images/';

    /*
its a pre-defined function which is taking an object $photo from the index.blade.php or whereever it will be called
    like this user->photo , then it would automatically takes the File column and it will
    append  the Upload attribute before the image which we assigned above

    */
public function getFileAttribute($phot)
{

    return $this->uploads . $phot;
}







}

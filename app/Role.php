<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /*protected $fillable = [
       'id', 'name',
    ];*/

    protected $guarded = [''];
    public function permission(){
        return $this->hasMany('App\Permission');
    }
}
//$filable=['name'];
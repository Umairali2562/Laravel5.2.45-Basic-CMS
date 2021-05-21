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
        $permissions = Permission::whereIn('id', json_decode($this->attributes['permissions']))->get();


        return $permissions;
    }

    public function hasAccess($permissionName)
    {

    }
}
//$filable=['name'];
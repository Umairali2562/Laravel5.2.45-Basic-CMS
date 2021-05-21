<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersEditRequest;
use App\Http\Requests\UsersRequest;
use App\Photo;
use App\User;
use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users=user::all();
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles=Role::lists('name','id')->all();
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {

        if(trim($request->password)==''){
            $input=$request->except('password');
        }else{
            $input=$request->all();
            $input['password']=bcrypt($request->password);// encrypting the password from the request variable , and storing it in the input array

        }



        if($file=$request->file('photo_id')){  //check if the user has submit photo from the form
           $name=time().$file->getClientOriginalName(); //append the time in the beginning to make image unique
           $file->move('images',$name); //move the image to the image folder in public dir
           $photo=photo::create(['file'=>$name]); //inserting the image in the photo table , file column
           $input['photo_id']=$photo->id; //getting its ID of the inserted record and storing it in the input array

        }
       //$input['password']=bcrypt($request->password);// encrypting the password from the request variable , and storing it in the input array
        user::create($input); //inserting all the input array in the user's table which means photo_id as well above

        return redirect('/admin/users'); //returning the user to the display users

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=user::findOrFail($id);
       ///$userid=$user->role_id;
       //$roles=role::find($userid);
      // $roles=$roles->name;

        $roles=Role::lists('name','id')->all();
       // return view('admin.users.create',compact('roles'));
        return view('admin.users.edit',compact('user','roles'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersEditRequest $request, $id)
    {

        $user=User::findOrFail($id);
        if(trim($request->password)==''){
            $input=$request->except('password');
        }else{
            $input=$request->all();
            $input['password']=bcrypt($request->password);// encrypting the password from the request variable , and storing it in the input array

        }


        if($file=$request->file('photo_id')){

            $name=time().$file->getClientOriginalName();
            $file->move('images',$name);
            $photo=Photo::create(['file'=>$name]);
            $input['photo_id']=$photo->id;
        }

        $user->update($input);
        return redirect('admin/users');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::findOrFail($id);
        unlink(str_replace("","/",public_path()).str_replace("..","",$user->photo->file));
        $user->delete();

        Session::flash('deleted_user','The User Has been Deleted..!!');
        return redirect('/admin/users');
    }
}

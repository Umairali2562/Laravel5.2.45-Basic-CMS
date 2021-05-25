@extends('layouts.admin')



@section('content')
    <h1>Create Users</h1>

    <div class="row">

        <div class="col-sm-3">


            <img src="{{$user->photo?  str_replace("../","../../../",$user->photo->file) : 'http://placehold.it//400x400'}}" height='140px'>

        </div>

        <div class="col-sm-9">
    {!! Form::model($user,['method'=>'PATCH','action'=>['AdminUsersController@update',$user->id], 'files'=>true]) !!}
    <div class="form-group">
        {!! Form::label('Name','Name:') !!}
        {!! Form::text('name',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('email','Email:') !!}
        {!! Form::text('email',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('role_id','Role:') !!}
        {!! Form::select('role_id',$roles,null,['class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('is_active','Status:') !!}
        {!! Form::select('is_active',array(1=>'Active',0=>'Not Active'),0,['class'=>'form-control']) !!}
    </div>


    <div class="form-group">
        {!! Form::label('password','Password:') !!}
        {!! Form::password('password',['class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Photo_id','Image:') !!}
        {!! Form::file('photo_id',['class'=>'form-control']) !!}
    </div>


    <div class="form-group">
        {!! Form::submit('Update User',['class'=>'btn btn-primary col-sm-2 mybtn']) !!}
    {!! Form::close() !!}


    </div>


        {!! Form::open(['method'=>'DELETE','action'=>['AdminUsersController@destroy',$user->id]]) !!}

        <div class="form-group">
            {!! Form::submit('Delete User',['class'=>'btn btn-danger col-sm-2 mybtn']) !!}
        </div>

        {!! Form::close() !!}
        </div>
        </div>


    <div class="row">
        @include('includes.form_error')
    </div>

@stop

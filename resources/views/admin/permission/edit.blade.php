@extends('layouts.admin')



@section('content')
    <h1>Edit Roles & Permissions</h1>
    {!! Form::model($roles,['method'=>'PATCH','action'=>['UserPermissionsController@update',$roles->id], 'files'=>true]) !!}
    <div class="form-group">
        {!! Form::label('Name','Name:') !!}
        {!! Form::text('name',null,['class'=>'form-control']) !!}
    </div>



    <div class="form-group">


        @foreach($allpermissions as $allpermission)
            @foreach($roles->permission() as $permission)

                @if($allpermission->name==$permission->name)
                    @php
                        $counter=1;
                    @endphp
                @break
                @else
                    @php
                        $counter=2;
                    @endphp
                    @endif

            @endforeach

                @if(!isset($counter))
                    @php
                        $counter=2;
                    @endphp

                @endif

                <label>{{$allpermission->name}}</label>
                <input type="checkbox" {{$counter==1?'checked':''}}  name="name[]"  value="{{$allpermission->name}}">


        @endforeach
    </div>




    <div class="form-group">
        {!! Form::submit('Update Role',['class'=>'btn btn-primary col-sm-2 mybtn']) !!}
        {!! Form::close() !!}

    </div>


    {!! Form::open(['method'=>'DELETE','action'=>['UserPermissionsController@destroy',$roles->id]]) !!}

    <div class="form-group">
        {!! Form::submit('Delete Role',['class'=>'btn btn-danger col-sm-2 mybtn']) !!}
    </div>

    {!! Form::close() !!}




    <div class="row">
        @include('includes.form_error')
    </div>

@stop

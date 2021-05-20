@extends("layouts.admin")

@section('content')
    <h1>Create Permission</h1>

        <div class="row">
          {!! Form::open(['method'=>'POST','action'=>'UserPermissionsController@store','files'=>true]) !!}




            <div class="form-group">
                {!! Form::label('role_id','Role:') !!}
                {!! Form::select('role_id',[''=>'Choose Options']+$roles,null,['class'=>'form-control']) !!}
            </div>


                <div class="form-group">
                    @foreach($permissions as $permission)
                        {!! Form::label('permission_id'," ".$permission->name) !!}
                  {{ Form::checkbox($permission->name,$permission->id,null, array('id'=>'asap')) }}
                    @endforeach
                </div>


              <div class="form-group">
                  {!! Form::submit('Create Roles',['class'=>'btn btn-primary']) !!}
              </div>

              {!! Form::close() !!}
            </div>

            <div class="row">
                @include('includes.form_error')
            </div>

@stop

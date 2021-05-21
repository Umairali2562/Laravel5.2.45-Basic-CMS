@extends("layouts.admin")

@section('content')
    <h1>Create Roles & Permissions</h1>

        <div class="row">
          {!! Form::open(['method'=>'POST','action'=>'UserPermissionsController@store','files'=>true]) !!}




            <div class="form-group">
                {!! Form::label('label','Role:') !!}
                {!! Form::text('title',null,['class'=>'form-control']) !!}
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

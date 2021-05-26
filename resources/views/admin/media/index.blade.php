@extends('layouts.admin')

@section('content')
<h1>Media</h1>
@if($photos)
    <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Updated</th>
              </tr>
            </thead>
            <tbody>

@foreach($photos as $photo)

              <tr>
             <td>{{$photo->id}}</td>
             <td> <img height="50px" src="{{$photo->file}}"> </td>
             <td>{{$photo->created_at?$photo->created_at:'No Dates'}}</td>

                  <td>

                      {!! Form::open(['method'=>'DELETE','action'=>['AdminMediasController@destroy',$photo->id]]) !!}

                      <div class="form-group">
                          {!! Form::submit('Delete',['class'=>'btn btn-danger col-sm-4 mybtn']) !!}
                      </div>

                      {!! Form::close() !!}
                  </td>
              </tr>

@endforeach

           </tbody>
         </table>


    @endif
@stop
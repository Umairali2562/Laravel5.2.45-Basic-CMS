@extends('layouts.admin')


@section('content')

    <h1>Posts</h1>
 <table class="table">
         <thead>
           <tr>
             <th>ID</th>
             <th>user</th>
             <th>Category</th>
             <th>Photo</th>
             <th>title</th>
             <th>body</th>
             <th>Created</th>
             <th>Updated</th>
           </tr>
         </thead>
         <tbody>


        @if($posts)

            @foreach($posts as $post)
           <tr>

               <td>{{$post->id}}</td>
               <td>{{$post->user->name}}</td>
               <td>{{$post->user_category}}</td>
              
               <td><img src="{{$post->photo? $post->photo->file : 'http://placehold.it//400x400'}}" height='50px'></td>

               <td>{{$post->title}}</td>
               <td>{{$post->body}}</td>
               <td>{{$post->created_at->diffforhumans()}}</td>
               <td>{{$post->updated_at->diffforhumans()}}</td>
           </tr>
            @endforeach

        @endif


        </tbody>
      </table>


@endsection


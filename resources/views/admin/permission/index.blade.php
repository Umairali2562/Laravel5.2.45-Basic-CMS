@extends('layouts.admin')


@section('content')

    <h1>Roles & Permissions</h1>

    @if(Session::has('deleted_user'))
        <p class="bg-danger">{{session('deleted_user')}}</p>
    @endif
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Permissions</th>
            <th>Created</th>
            <th>Updated</th>
        </tr>
        </thead>
        <tbody>



        @if($roles)

            @foreach($roles as $role)



                <tr>
                    <td>{{$id=$role->id}}</td>
                    <td><a href="{{route('admin.permissions.edit',$role->id)}}">{{$role->name}}</a></td>
                    <td>
                        @php
                        $No="No Permission";
                    @endphp

                        @foreach($role->permission() as $permission)
                    {{$permission?$permission->name:$No}}
                    @endforeach
                    </td>
                    <td>{{$role->created_at?$role->created_at->diffForhumans():'..'}}</td>
                    <td>{{$role->updated_at?$role->updated_at->diffForhumans():'..'}}</td>
                </tr>

            @endforeach

        @endif

        </tbody>
    </table>
@stop
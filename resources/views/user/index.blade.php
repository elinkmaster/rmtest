@extends('layouts.authenticated')


@section('content')
<div class="container">
    <div class="row justify-content-center align-content-center mt-5">
        <div class="card col-md-5 p-4 shadow">
            <h5 class="text-center">User Profile</h5>
            <form action="{{route('user.update-profile')}}" method="post">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span>{{ $message }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                @csrf
                <div class="form-group my-2">
                    <label for="firstname">Firstname</label>
                    <input type="text" name="firstname" id="firstname" class="form-control" value="{{auth()->user()->firstname ?? old('firstname')}}">
                    @error('firstname')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <label for="lastname">Lastname</label>
                    <input type="text" name="lastname" id="lastname" class="form-control" value="{{auth()->user()->lastname ?? old('lastname')}}">
                    @error('lastname')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <label for="middlename">Middlename</label>
                    <input type="text" name="middlename" id="middlename" class="form-control" value="{{auth()->user()->middlename ?? old('middlename')}}">
                </div>
                <div class="form-group my-2">
                    <label for="">Email</label>
                    <input type="email" name="email" id="email" readonly class="form-control" value="{{auth()->user()->email}}">
                    <small class="text-secondary ms-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                        Email addresses are unique modifiers, thus they cannot be updated.
                    </small>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Update Profile</button>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

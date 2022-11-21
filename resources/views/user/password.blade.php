@extends('layouts.authenticated')


@section('content')
<div class="container">
    <div class="row justify-content-center align-content-center mt-5">
        <div class="card col-md-5 p-4 shadow">
            <h5 class="text-center">User Change Password</h6>
            <form action="{{route('user.update-password')}}" method="post">
            @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span>{{ $message }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                @csrf
                <div class="form-group my-2">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control">
                    @error('current_password')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    @error('new_password')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Change Password</button>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection


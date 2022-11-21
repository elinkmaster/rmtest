@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-content-center vh-100">
            <div class="col-md-4 ">
                <div class="d-flex justify-content-center gap-2">
                    <img src="{{asset('images/readers_magnet.png')}}" height="100" width="100" alt="" srcset="">
                    {{-- <img src="{{asset('images/readers_magnet.png')}}" height="90" width="90" alt="" srcset=""> --}}
                </div>
                <h5 class="text-center my-2">Royalty Calculation</h5>
                <form action="" method="post" class="card p-4 shadow">
                    @csrf
                    <div class="form-group my-2">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" placeholder="Email Address" class="form-control">
                        @error('email')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="xxxxxxx" class="form-control">
                    </div>
                    <div class="form-group my-1">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.authenticated')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-content-center mt-5">
            <div class="col-md-5">
                <form action="{{ route('pod.import-bulk') }}" method="post" class="card p-4 shadow"
                    enctype="multipart/form-data">
                    <a href="{{ route('pod.index') }}" class="ms-auto text-decoration-none text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-x" viewBox="0 0 16 16">
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </a>
                    <h5 class="text-center">Import File</h5>
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <span>{{ Session::get('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @csrf
                    <div class="form-group my-2">
                        <label for="email">Excel File</label>
                        <input type="file" name="file" id="file" class="form-control">
                        @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="row justify-content-center">
                        <div class="col form-group">
                            <label for="month">Month</label>
                            <select name="month" id="month" class="form-select">
                                @foreach ($months as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="year">Year</label>
                            <select name="year" id="year" class="form-select">
                                <option value="">Select year</option>
                                @for ($x = $year->year ?? $year; $x <= now()->year; $x++)
                                    <option value="{{ $x }}">{{ $x }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group my-2 ">
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

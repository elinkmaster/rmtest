@extends('layouts.authenticated')

@section('content')
<div class="container">
    <div class="container">
        <div class="row justify-content-center align-content-center mt-5">
            <div class="col-md-5">
                <form action="" method="post" class="card p-4 shadow">
                    <div class="w-100 d-flex">
                        <a href="{{route('ebook.index')}}" class="ms-auto text-decoration-none text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-x" viewBox="0 0 16 16">
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </a>
                    </div>
                    <h5 class="text-center">Add Transaction</h5>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span>{{ $message }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @csrf
                    <div class="form-group my-1">
                        <label for="author">Author</label>
                        <select name="author" id="author" class="form-select select2" required>
                            <option value="" disabled selected>Select author</option>
                            @foreach ($authors as $author)
                                <option value="{{$author->id}}">{{$author->getFullName()}}</option>
                            @endforeach
                        </select>
                        @error('author')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="book">Book</label>
                        <select name="book" id="book" class="form-select select2" required>
                            <option value="" disabled selected>Select book</option>
                            @foreach ($books as $book)
                                <option value="{{$book->id}}">{{$book->title}}</option>
                            @endforeach
                        </select>
                        @error('book')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="year">Year</label>
                        <input type="text" class="form-control" name="year" id="year" value="{{old('year')}}" required>
                        @error('year')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="month">Month</label>
                        <select name="month" id="month" class="form-select" required>
                            <option value="" disabled selected>Select month</option>
                            @foreach ($months as $key => $value)
                                @if (old('month') == $key)
                                    <option value="{{$key}}" selected>{{$value}}</option>
                                @else
                                    <option value="{{$key}}">{{$value}}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('month')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{old('quantity')}}" required>
                        @error('quantity')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="price">Retail Price</label>
                        <input type="number" name="price" id="price" class="form-control" value="{{old('price')}}" required>
                        @error('price')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="proceeds">Proceeds of Sale Due Publisher</label>
                        <input type="number" name="proceeds" id="proceeds" class="form-control" value="{{old('proceeds')}}" required>
                        @error('proceeds')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <button type="submit" class="btn btn-primary">Add Transaction</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
   $(document).ready(function(){
  $("#year").datepicker({
     format: "yyyy",
     viewMode: "years",
     minViewMode: "years",
     autoclose:true
  });
  $('.select2').select2();
})
</script>
@endsection

@extends('layouts.authenticated')
@section('content')
    <div class="container ">
        <form action="{{ route('rejecteds-ebooks.update', ['rejected_ebook' => $ebook]) }}" method="post">
            @method('PUT')
            @csrf
            <h5 class="text-center mt-4">Update Rejected Ebook Transaction</h5>
            <div class="d-flex">
                <button type="submit" class="btn btn-success btn-sm ms-auto mx-5">Accept Update</button>
            </div>
            <div class="row justify-content-center gap-4 my-3">
                <div class="col-md-5 card shadow p-3">
                    <h6 class="text-center">Data From</h6>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" id="author_name" value="{{ $ebook->author_name }}" class="form-control"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label>Book</label>
                        <textarea id="book_title" cols="10" rows="3" class="form-control" disabled>{{ $ebook->book_title }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Class of Trade</label>
                        <input type="text" id="class_of_trade" cols="10" rows="3" class="form-control"
                            value="{{ $ebook->class_of_trade }}" disabled>
                    </div>

                    <div class="form-group">
                        <label>Line Item Number</label>
                        <input type="text" id="line_item_no" cols="10" rows="3" class="form-control"
                            value="{{ $ebook->line_item_no }}" disabled>
                    </div>

                    <div class="form-group my-1">
                        <label>Year</label>
                        <input type="text" class="form-control" id="ebook_year" value="{{ $ebook->year }}" disabled>

                    </div>
                    <div class="form-group my-2">
                        <label>Month</label>
                        <input type="text" id="ebook_month" class="form-control"
                            value="{{ App\Helpers\MonthHelper::getStringMonth($ebook->month) }}" disabled>

                    </div>


                    <div class="form-group my-1">
                        <label>Quantity</label>
                        <input type="number" id="quantity" class="form-control" value="{{ $ebook->quantity }}" disabled>

                    </div>
                    <div class="form-group my-1">
                        <label>Retail Price</label>
                        <input type="number" id="price" class="form-control" value="{{ $ebook->price }}" disabled>
                    </div>
                    <div class="form-group my-1">
                        <label>Proceeds of Sale Due Publisher</label>
                        <input type="number" id="price" class="form-control" value="{{ $ebook->proceeds }}" disabled>
                        <input type="number" id="royalty" class="form-control" value="{{ $ebook->royalty }}" disabled>
                    </div>
                </div>

                <div class="bg-light p-3 shadow rounded col-md-5">
                    <h6 class="text-center">Update To</h6>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <select name="author" class="select2 form-control" id="author">
                            <option value="" disabled selected>Please select one</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->getFullName2() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="book">Book</label>
                        <input type="text" name="book" id="book" cols="10" rows="3" class="form-control"
                            value=" {{$ebook->book_title}}">
                      
                    </div>

                    <div class="form-group">
                        <label>Class of Trade</label>
                        <input type="text"name ="class_of_trade" id="class_of_trade" cols="10" rows="3" class="form-control"
                            value="{{$ebook->class_of_trade }}">
                    </div>

                    <div class="form-group">
                        <label>Line Item Number</label>
                        <input type="text" name="line_item_no" id="line_item_no" cols="10" rows="3" class="form-control"
                            value="{{ old('line_item_no') ?? $ebook->line_item_no }}">
                    </div>


                    <div class="form-group my-1">
                        <label for="year">Year</label>
                        <input type="text" class="form-control" name="year" id="year"
                            value="{{ old('year') ?? $ebook->year }}">

                    </div>
                    <div class="form-group my-2">
                        <label for="month">Month</label>
                        <select name="month" id="month" class="form-select">
                            <option value="" disabled selected>Select month</option>
                            @foreach ($months as $key => $value)
                                @if (old('month') == $key || $ebook->month == $key)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group my-1">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control"
                            value="{{ old('quantity') ?? $ebook->quantity }}">
                        @error('quantity')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="price">Retail Price</label>
                        <input type="number" name="price" id="price" class="form-control"
                            value="{{ old('price') ?? $ebook->price }}">
                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="proceeds">Proceeds of Sale Due Publisher</label>
                        <input type="number" name="proceeds" id="proceeds" class="form-control"
                            value="{{ old('proceeds') ?? $ebook->proceeds }}">
                        @error('proceeds')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="price">royalty</label>
                        <input type="number" name="royalty" id="price" class="form-control"
                            value="{{ old('royalty') ?? $ebook->royalty }}">
                        @error('royalty')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </form>

    </div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#year").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });
            $('.select2').select2();
        });
    </script>
@endsection

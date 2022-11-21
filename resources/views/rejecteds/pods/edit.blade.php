@extends('layouts.authenticated')
@section('content')
    <div class="container ">
        <form action="{{ route('rejecteds-pods.update', ['rejected_pod' => $pod]) }}" method="post">
            @method('PUT')
            @csrf
            <h5 class="text-center mt-4">Update Rejected Pod Transaction</h5>
            <div class="d-flex">
                <button type="submit" class="btn btn-success btn-sm ms-auto mx-5">Accept Update</button>
            </div>
            <div class="row justify-content-center gap-4 my-3">
                <div class="col-md-5 card shadow p-3">
                    <h6 class="text-center">Data From</h6>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" id="author_name" value="{{ $pod->author_name }}" class="form-control"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label>Book</label>
                        <textarea id="book_title" cols="10" rows="3" class="form-control" disabled>{{ $pod->book_title }}</textarea>
                    </div>
                    <div class="form-group my-1">
                        <label>ISBN</label>
                        <input name="isbn" type="text" class="form-control" id="pod_year" value="{{ $pod->isbn }}" disabled>
                        <input type="hidden" name='market' class="form-control" id="pod_year" value="{{ $pod->market }}" disabled>
                    </div>

                    <div class="form-group my-1">
                        <label>Year</label>
                        <input type="text" class="form-control" id="pod_year" value="{{ $pod->year }}" disabled>

                    </div>
                    <div class="form-group my-2">
                        <label>Month</label>
                        <input type="text" id="pod_month" class="form-control"
                            value="{{ App\Helpers\MonthHelper::getStringMonth($pod->month) }}" disabled>

                    </div>
                    <div class="form-group my-1">
                        <label>Flag</label>
                        <input type="text" id="pod_flag" class="form-control" value="{{ Str::title($pod->flag) }}"
                            disabled>
                    </div>
                    <div class="form-group my-1">
                        <label>Status</label>
                        <select class="form-control" disabled>
                            <option value="" {{ $pod->status == '' ? 'selected' : '' }}>Unpaid</option>
                            <option value="Paid" {{ $pod->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>

                    </div>
                    <div class="form-group my-1">
                        <label>Format</label>
                        <select class="form-control" disabled>
                            <option value="" disabled selected>Select format</option>
                            <option value="Paperback" {{ $pod->format == 'Paperback' ? 'selected' : '' }}>Paperback
                            </option>
                            <option value="Hardback" {{ $pod->format == 'Hardback' ? 'selected' : '' }}>Hardback
                            </option>
                            <option value="Perfectbound" {{ $pod->format == 'Perfectbound' ? 'selected' : '' }}>
                                Perfectbound
                            </option>
                            <option value="Trade Cloth/Laminate"
                                {{ $pod->format == 'Trade Cloth/Laminate' ? 'selected' : '' }}>Trade Cloth/Laminate
                            </option>
                        </select>

                    </div>
                    <div class="form-group my-1">
                        <label>Quantity</label>
                        <input type="number" id="quantity" class="form-control"
                            value="{{ old('quantity') ?? $pod->quantity }}" disabled>

                    </div>
                    <div class="form-group my-1">
                        <label>Price</label>
                        <input type="number" id="price" class="form-control" value="{{ old('price') ?? $pod->price }}"
                            disabled>

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
                        <textarea id="book" name="book" cols="10" rows="3" class="form-control">{{ $pod->book_title }}
                        </textarea>
                    </div>
                    <div class="form-group my-1">
                        <label for="isbn">ISBN</label>
                        <input type="text" class="form-control" name="isbn" id="isbn"
                            value="{{ old('isbn') ?? $pod->isbn }}">
                    </div>
                    <div class="form-group my-1">
                        <label for="market">Market</label>
                        <input type="text" class="form-control" name="market" id="isbn"
                            value="{{ old('market') ?? $pod->market }}">
                    </div>
                    <div class="form-group my-1">
                        <label for="year">Year</label>
                        <input type="text" class="form-control" name="year" id="year"
                            value="{{ old('year') ?? $pod->year }}">

                    </div>

                    <div class="form-group my-2">
                        <label for="month">Month</label>
                        <select name="month" id="month" class="form-select">
                            <option value="" disabled selected>Select month</option>
                            @foreach ($months as $key => $value)
                                @if (old('month') == $key || $pod->month == $key)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group my-1">
                        <label for="flag">Flag</label>
                        <select name="flag" class="form-control" required>
                            <option value="" disabled selected>Select flag</option>
                            <option value="Yes" {{ Str::title($pod->flag) == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ Str::title($pod->flag) == 'No' ? 'selected' : '' }}>No</option>
                        </select>

                    </div>
                    <div class="form-group my-1">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="" {{ $pod->status == '' ? 'selected' : '' }}>Unpaid</option>
                            <option value="Paid" {{ $pod->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>

                    </div>
                    <div class="form-group my-1">
                        <label for="format">Format</label>
                        <select name="format" class="form-control" required>
                            <option value="" disabled selected>Select format</option>
                            <option value="Paperback" {{ $pod->format == 'Paperback' ? 'selected' : '' }}>Paperback
                            </option>
                            <option value="Hardback" {{ $pod->format == 'Hardback' ? 'selected' : '' }}>Hardback
                            </option>
                            <option value="Perfectbound" {{ $pod->format == 'Perfectbound' ? 'selected' : '' }}>
                                Perfectbound
                            </option>
                            <option value="Trade Cloth/Laminate"
                                {{ $pod->format == 'Trade Cloth/Laminate' ? 'selected' : '' }}>Trade Cloth/Laminate
                            </option>
                        </select>

                    </div>
                    <div class="form-group my-1">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control"
                            value="{{ old('quantity') ?? $pod->quantity }}">

                    </div>
                    <div class="form-group my-1">
                        <label for="price">Price</label>
                        <input type="number" name="price" id="price" class="form-control"
                            value="{{ old('price') ?? $pod->price }}">

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

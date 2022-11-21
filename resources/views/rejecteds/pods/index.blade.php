@extends('layouts.authenticated')

@section('content')
    <div class="container ">
        <div class="p-3 my-3 w-100 ">
            <form action="" method="get">

                <div class="d-flex gap-2" style="width: 30%">
                    <label for="filter">Search for Books</label>
                    <input type="text" name="filter" id="filter" class="form-control">


                </div>
                <div class ="d-flex-gap-2" style="width:20%">
                <label for="month">Filter by Month</label>
                        <select name="month" id="month" class="form-select">
                            @foreach ($months as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Search</button>
            </form>
            <a href="{{ route('all-rejecteds-pods.clear') }}"
                                            onclick="return confirm('Are you sure you want to Clear file?')"
                                            class="btn btn-danger"> Clear All</a>
            <div class="bg-light p-2 shadow rounded">
                <h5 class="text-center my-3">Rejected POD Transactions</h5>
                <table class="table table-bordered table-hover mt-2">
                    <thead>
                        <tr class="text-center">
                            <th>Author</th>
                            <th>Book</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Flag</th>
                            <th>Status</th>
                            <th>Format</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Royalty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pods as $pod)
                            <tr>
                                <td>{{ Str::title($pod->author_name) }}</td>
                                <td>{{ Str::title($pod->book_title) }}</td>
                                <td>{{ $pod->year }}</td>
                                <td>{{ App\Helpers\MonthHelper::getStringMonth($pod->month) }}</td>
                                <td>{{ $pod->flag }}</td>
                                <td>{{ $pod->status }}</td>
                                <td>{{ $pod->format }}</td>
                                <td>{{ $pod->quantity }}</td>
                                <td>${{ $pod->price }}</td>
                                <td>${{ $pod->royalty }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('rejecteds-pods.edit', ['rejected_pod' => $pod]) }}"
                                            class="btn btn-outline-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('rejecteds-pods.delete', ['rejected_pod' => $pod]) }}"
                                            onclick="return confirm('Are you sure you want to delete this file?')"
                                            class="btn btn-outline-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                <path fill-rule="evenodd"
                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No record found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                {{ $pods->withQueryString()->links() }}
            </div>
        </div>
    </div>
    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection

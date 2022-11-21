@extends('layouts.authenticated')

@section('content')
    <div class="container ">
        <div class="p-3 my-3 w-100 ">
            <div class="d-flex">
            
                <form action="{{ route('royalty.search') }}" method="get" class="d-flex gap-2">
                    <div class="form-group my-2">
                        <select name="author_id" id="author_id" class="form-control select2 w-50">
                            <option value="all" selected>Search Author</option>
                            @foreach ($author as $x)
                                @if (request()->get('id') == $x->id)
                                    <option value="{{ $x->id }}" selected>{{ $x->firstname}} {{ $x->lastname}}</option>
                                @else
                                    <option value="{{ $x->id }}">{{ $x->firstname}} {{ $x->lastname}}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
                    <div class="d-flex-right">
                     
                    <span>
                    <form action="{{ route('royalty.sort') }}" method="get">
                    <label >Sort</label>
                  
                        
                        <select name="sort" value="Sort" id="sort" class="form-control">
                          <option value="ASC">Sort By Author (ASC)</option>
                          <option value="DESC">Sort By Author (DESC)</option>
                          <option value="RASC">Sort By Author and Royalty (ASC)</option>
                          <option value="RDSC">Sort By Author and Royalty (DESC)</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">
                           SORT
                        </button>
            
                </form>
                </span>
                    </div>   
                    <div class="d-flex-right" style="padding-left:525px;">
                     
                     <span>
                     <form action="{{ route('royalty.sort') }}" method="get">
                     <label >Filter By month</label>
                     <select name="months" id="months" class="form-select">
                                <option value="" disabled selected>Select one</option>
                                <option value="all" >All</option>
                                @foreach ($months as $key => $value)
                                   
                                        <option value="{{$key}}" selected>{{$value}}</option>
                                   
                                @endforeach
                            </select>
                         
                        
                         <button type="submit" class="btn btn-sm btn-primary">
                            SORT
                         </button>
             
                 </form>
                 </span>
                     </div>  
                <div class="ms-auto">
                 
                </div>
            </div>
            <div class="bg-light p-2 shadow rounded">
                <h5 class="text-center my-3">Pod Royalty</h5>
                <table class="table table-bordered table-hover mt-2">
                    <thead>
                        <tr class="text-center">
                            <th>Author</th>
                            <th>Book</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Format</th>
                            <th>CopySold</th>
                            <th>Price</th>
                            <th>Revenue</th>
                            <th>Royalty</th>
                    
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pod_transactions as $pod_transaction)
                            <tr>
                             
                                <td>{{ $pod_transaction->author->firstname }} {{ $pod_transaction->author->lastname }}</td>
                                <td>{{ Str::title($pod_transaction->book->title) }}</td>
                                <td>{{ $pod_transaction->year }}</td>
                                <td>{{ App\Helpers\MonthHelper::getStringMonth($pod_transaction->month) }}</td>
                                @if( $pod_transaction->format == 'Perfectbound')
                                <td>Paperback</td>
                                @elseif( $pod_transaction->format == 'Trade Cloth/Laminate')
                                <td>Hardback</td>
                                @endif
                                
                                <td>{{ $pod_transaction->quantity }}</td>
                               
                                <td>${{ $pod_transaction->price }}</td>
                              
                                <td>${{ $pod_transaction->price * $pod_transaction->quantity  }}</td>
                             
                             
                                <td>${{ $pod_transaction->royalty }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        {{-- <a href="{{ route('pod.edit', ['pod' => $pod_transaction]) }}"
                                            class="btn btn-outline-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                            </svg>
                                        </a> --}}
                                        <a href="{{ route('pod.delete', ['pod' => $pod_transaction]) }}"
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
                {{ $pod_transactions->withQueryString()->links() }}
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

@extends('layouts.authenticated')

@section('content')
<div class="container">
    <div class="container">
        <div class="row justify-content-center align-content-center mt-5">
            <div class="col-md-5">
                <form action="{{route('generate.pdf')}}" method="POST" class="card p-4 shadow">
                    <h5 class="text-center">Quick Search</h5>
                    @csrf
                    <div class="form-group my-1">
                        <label for="author">Author</label>
                        <select name="author" id="author" class="form-select select2">
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
                        <select name="book[]" multiple="multiple" id="book" class="form-select select2">
                        </select>
                        @error('book')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <div class="form-group my-1 w-100 card p-2">
                            <h6 class="text-center">Date From</h6>
                            <label for="fromYear">Year</label>
                            <select name="fromYear" id="fromYear" class="form-select">
                                <option value="" disabled selected>Select one</option>
                            </select>
                            @error('fromYear')
                                <small class="text-danger">{{$message}}</small>
                            @enderror

                            <label for="fromMonth">Month</label>
                            <select name="fromMonth" id="fromMonth" class="form-select">
                                <option value="" disabled selected>Select one</option>
                                @foreach ($months as $key => $value)
                                    @if (old('fromMonth') == $key)
                                        <option value="{{$key}}" selected>{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('fromMonth')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group my-1 w-100 card p-2">
                            <h6 class="text-center">Date To</h6>
                            <label for="toYear">Year</label>
                            <select name="toYear" id="toYear" class="form-select">
                                <option value="" disabled selected>Select one</option>
                            </select>
                            @error('toYear')
                                <small class="text-danger">{{$message}}</small>
                            @enderror

                            <label for="toMonth">Month</label>
                            <select name="toMonth" id="toMonth" class="form-select">
                                <option value="" disabled selected>Select one</option>
                                @foreach ($months as $key => $value)
                                    @if (old('toMonth') == $key)
                                        <option value="{{$key}}" selected>{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('toMonth')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                            

                        </div>
                    </div>

                    <div class="form-group my-1">
                        <button name="preview" type="submit" class="btn btn-primary">Preview</button>
                        <button name="print" type="submit" class="btn btn-success">Print</button>
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
        
        $('.select2').select2();
        // Id of dropdown
        $('#author').change(async() => {
            //get the #book element (dropdown)
            let element = document.getElementById('book')
            //remove existing data in dropdown (#book)
            removeOptions(element)
            let fromYear = document.getElementById('fromYear')
            let toYear = document.getElementById('toYear')
            removeOptions(fromYear)
            removeOptions(toYear)
            //fetch data from the server base on user id
            const response = await fetch('/transaction/' + $('#author').val());
            //convert response to json
            let data = await response.json()
            //add the data to dropdoen, from the server which is the response
            createOptions(element, data.books, 'book')
            createOptions(fromYear, data.dates, 'year')
            createOptions(toYear, data.dates, 'year')
        });
        
        const removeOptions = (element) => {
            while(element.length > 1){
                element.remove(element.length - 1)
            }
        }
        const createOptions = (element, items, type) => {
            if(items.length > 0){
               
                items.forEach((item) => {
                    var opt = document.createElement('option')
                    if(type === 'book'){
                        opt.value = item.book_id
                        opt.innerText = item.book_title
                    }else{
                        opt.value = item
                        opt.innerText = item
                    }
                    element.appendChild(opt)
                })
            }else{
                var opt = document.createElement('option')
                opt.innerText = "No data found";
                element.appendChild(opt)
            }
        }
    })
</script>
@endsection
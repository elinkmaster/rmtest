@extends('layouts.authenticated')

@section('content')
    <div class="container ">
        <div class="p-3 my-3 w-100 ">
            <div class="d-flex">
           <div class="details" style="margin-top: 30px; width: 250px;">
       
        <h6 class="mt-4" style="font-size: 15px;"></h6>
      
    </div>
    <div class="details" style="margin-top: 30px;">
   
    </div>
    @if(empty($ebooks)  || empty($pods)   )
      <div class="bg-light p-2 shadow rounded" style="width: 1450px; height: 500px;">
      <label>Author's Name: </label>   <span style="font-size: 15px; mb-5;"> <b>{{$author->getFullName()}}</b>,</span> No data found On that s<br>
     
      <p style ="text-align: center; padding-top:40px;"><img src="{{asset('images/pngegg.png')}}" width="260px" height="260px" ></p>
    </div>
    @else
 
        @if(count($pods) > 0)
        <div class="bg-light p-2 shadow rounded"style="width: 1450px; height: 500px;">
        <label>Author's Name: </label>   <span style="font-size: 15px; mb-5;"> <b>{{$author->getFullName()}}</b>,</span>
       <br>
        <span>Statement Period: <b>{{App\Helpers\MonthHelper::getStringMonth($fromMonth)}} {{$fromYear}}</b> to <b>{{App\Helpers\MonthHelper::getStringMonth($toMonth)}} {{$toYear}}</b></span>
        <table class="table table-bordered table-hover mt-2">
        <thead>
                 <tr class="text-center">
                    <th> Book Title</th>
                    <th> Ref #</th>
                    <th>Format</th>
                    <th >Month</th>
                    <th>Year</th>
                    <th>Copies Sold</th>
                    <th>Retail Price</th>
                  
                    <th>15% Royalty</th>
                </tr>
            </thead>
            <tbody style="">
             
                @foreach ($pods as $pod)
                @if($pod['quantity'] > 0  )
                    @if(App\Helpers\UtilityHelper::hasTotalString($pod))
                        <tr>
                        <form action="{{route('generate.pdf')}}" method="POST" class="card p-4 shadow">
                        @csrf
                            <td colspan="4" style="border: 1px solid; width:90px; "><input hidden type="text" name="book[]" multiple="multiple" id="book" value="{{$pod['books']}}" class="form-select select2">
                        {{$pod['title']}}</td>
                        <td style="border: 1px solid; width:70px; text-align:center;"></td>

                            <td style="border: 1px solid; width:70px; text-align:center;">{{$pod['quantity']}}</td>
                            <td style="border: 1px solid; width:70px; text-align:center;">${{$pod['price']}}</td>
                            <td style="border: 1px solid; width:70px; text-align:center;">${{$pod['royalty']}}</td>
                        </tr>
                    @else
                        <tr>
                           
                            <td style="border: 1px solid; width:230px;" >{{$pod['title']}}</td>
                            <td style="border: 1px solid; width:50px; text-align:center;">{{$pod['refkey']}}</td>
                            <td style="border: 1px solid; width:90px; text-align:center;">{{$pod['format']}}</td>
                            <td style="border: 1px solid; width:50px; text-align:center;">{{App\Helpers\MonthHelper::getStringMonth($pod['month'])}}</td>
                           
                            <td style="border: 1px solid; width:50px; text-align:center;">{{$pod['year']}}</td>
                            <td style="border: 1px solid; width:70px; text-align:center;">{{$pod['quantity']}}</td>
                            <td style="border: 1px solid; width:70px; text-align:center;">{{$pod['price']}}</td>

                            <td style="border: 1px solid; width:70px; text-align:center;">{{substr($pod['royalty'],0,-1)}}</td>
                        @endif
                        </tr>
                    @endif
                @endforeach
                <tr>


                    <td colspan="4" style="border: 1px solid; width:90px; "><b>{{$totalPods['title']}}</b></td>
                    <td style="border: 1px solid; width:70px; text-align:center;"></td>
                    <td style="border: 1px solid; width:70px; text-align:center;"><b>{{$totalPods['quantity']}}</b></td>
                    <td style="border: 1px solid; width:70px; text-align:center;"><b> ${{$totalPods['price']}}</b></td>     
        

                    <td style="border: 1px solid; width:70px; text-align:center;"><b> <i> ${{($totalPods['royalty'])}}</i></b></td>
                </tr>
            </tbody>
        </table>
        
        <input hidden type="text" name="author" id="author" value="{{$author->id}}  ">
                    <input hidden type="text" name="authorname" id="authorname" value="{{$author->getFullName()}}">
                    <input hidden type="text" id="fromYear" name="fromYear" value="{{$fromYear}}">
                    <input hidden type="text" id="toYear" name="toYear" value="{{$toYear}}">
                    <input hidden type="text" id="fromMonth" name="fromMonth" value="{{$fromMonth}}">
                    <input hidden type="text" id="toMonth" name="toMonth" value="{{$toMonth}}">  
                    <input hidden type="text" name="actiontype" value="print">   
                    <div class="form-group my-1">
                    <a class="btn btn-primary" href = "{{route('dashboard')}}">Go Back Home</a> 
                    <button name="print" class="btn btn-success" type="submit">Print</button>  
            </form>
                </div>
      @else
    
      @endif
    </div>
    @if(count($ebooks) > 0)
    <div class="bg-light p-2 shadow rounded">
        <label>Author's Name: </label>   <span style="font-size: 15px; mb-5;"> <b>{{$author->getFullName()}}</b>,</span>

        <span>Statement Period: <b>{{App\Helpers\MonthHelper::getStringMonth($fromMonth)}} {{$fromYear}}</b> to <b>{{App\Helpers\MonthHelper::getStringMonth($toMonth)}} {{$toYear}}</b></span>
    <div class="transaction" style="margin-top: 30px;">
        <table class="table table-bordered table-hover mt-2">
            <thead style="background-color: #e3edf3;border: 1px solid;font-size: 12px;">
                <tr style="text-align:center;">
                    <th style="border: 1px solid;">eBook</th>
                    <th style="border: 1px solid;">Month</th>
                    <th style="border: 1px solid;">Year</th>
                    <th style="border: 1px solid;">TradeType</th>
                    <th style="border: 1px solid;">Quantity</th>
                    <th style="border: 1px solid;">Retail Price</th>
      
                    <th style="border: 1px solid;">Author Royalty</th>
                </tr>
            </thead>
            <tbody style="">
                @foreach ($ebooks as $ebook)
                    @if(App\Helpers\UtilityHelper::hasTotalString($ebook))
                    <tr>
                 
                    <form action="{{route('generate.pdf')}}" method="POST" class="card p-4 shadow">
                        @csrf
                       
                        <td colspan="3" style="border: 1px solid; width:90px; "><input hidden type="text" name="book[]" multiple="multiple" id="book" value="{{$ebook['books']}}" class="form-select select2"><b>{{$ebook['title']}}</b></td>
                        <td style="border: 1px solid; width:70px; text-align:center;"><b></b></td>
                        <td style="border: 1px solid; width:70px; text-align:center;">{{$ebook['quantity']}}</td>
                        <td style="border: 1px solid; width:70px; text-align:center;">${{$ebook['price']}}</td>

                        <td style="border: 1px solid; width:70px; text-align:center;">${{$ebook['royalty']}}</td>
                    </tr>
                    @else
                    <tr>
                    @if(!empty($ebook['trade']) && $ebook['quantity'] > 0  )
                        <td style="border: 1px solid; width:230px;" >{{$ebook['title']}}</td>
                        <td style="border: 1px solid; width:90px; text-align:center;">{{App\Helpers\MonthHelper::getStringMonth($ebook['month'])}}</td>
                        <td style="border: 1px solid; width:50px; text-align:center;">{{$ebook['year']}}</td>
                        <td style="border: 1px solid; width:50px; text-align:center;">{{$ebook['trade']}}</td>
                        <td style="border: 1px solid; width:50px; text-align:center;">{{$ebook['quantity']}}</td>
                        <td style="border: 1px solid; width:70px; text-align:center;">${{$ebook['price']}}</td>
       
                        <td style="border: 1px solid; width:70px; text-align:center;">${{$ebook['royalty']}}</td>
                   @endif
                    </tr>
                    @endif
                @endforeach
                <tr>
                    <td colspan="3" style="border: 1px solid; width:90px; "><b>${{$totalEbooks['title']}}</b></td>
                    <td style="border: 1px solid; width:70px; text-align:center;"><b></b></td>
                    <td style="border: 1px solid; width:70px; text-align:center;"><b>{{$totalEbooks['quantity']}}</b></td>
                    <td style="border: 1px solid; width:70px; text-align:center;"><b>${{$totalEbooks['price']}}</b></td>
                 
                    <td style="border: 1px solid; width:70px; text-align:center;"><b>${{number_format($totalEbooks['royalty'],2)}}</b></td>
                </tr>
            </tbody>
            
        </table>
       
            
            <input hidden type="text" name="author" id="author" value="{{$author->id}}  ">
                    <input hidden type="text" name="authorname" id="authorname" value="{{$author->getFullName()}}">
                    <input hidden type="text" id="fromYear" name="fromYear" value="{{$fromYear}}">
                    <input hidden type="text" id="toYear" name="toYear" value="{{$toYear}}">
                    <input hidden type="text" id="fromMonth" name="fromMonth" value="{{$fromMonth}}">
                    <input hidden type="text" id="toMonth" name="toMonth" value="{{$toMonth}}">  
                    <input hidden type="text" name="actiontype" value="print">   
                    <div class="form-group my-1">
                    <a class="btn btn-primary" href = "{{route('dashboard')}}">Go Back Home</a> 
                    <button name="print" class="btn btn-success" type="submit">Print</button>  
                    </form>   
                </div>
    </div>
   
    @endif
@endif
    <div>
    
</div>
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

        const createOptions = (element, items, type) => {
            if(items.length > 0){
               
                items.forEach((item) => {
                    var opt = document.createElement('option')
                    if(type === 'book'){
                        opt.value = item.book_id
                        opt.innerText = item.book_id
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
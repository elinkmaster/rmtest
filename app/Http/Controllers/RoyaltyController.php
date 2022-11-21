<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Helpers\MonthHelper;
use App\Helpers\NameHelper;
use App\Models\PodTransaction;
use Illuminate\Support\Facades\DB;

class RoyaltyController extends Controller
{
    public function index()
    {
        $months = MonthHelper::getMonths();
        $author = Author::get();
        $author = Author::all();
        foreach($author as $authors){
          
            $podlists = Podtransaction ::where('author_id',$authors->id);
            $podTransactionall = Podtransaction ::all();
            

           // $podtran = Podtransaction ::where('quantity' ,'<>', 0)->orderBy('author_id', 'ASC')->paginate(10);
            
           
        }   
        
        return view('royalties.pod', [
           
            'pod_transactions' => Podtransaction ::where('quantity' ,'<>', 0)->orderBy('author_id', 'ASC')->paginate(10)
           
        ], compact('author' , 'months'));
        
        
    }
    public function search(Request  $request)
    {
        if($request->author_id == 'all'){
            $author = Author::all();
            $months = MonthHelper::getMonths();
            return view('royalties.pod', [
                'pod_transactions' => PodTransaction::where('quantity' ,'<>', 0)->orderBy('author_id', 'ASC')->paginate(10)
            ], compact('author', 'months'));
        }else{
            $author = Author::all();
            $months = MonthHelper::getMonths();
        return view('royalties.pod', [
            'pod_transactions' => PodTransaction::where('author_id' , $request->author_id)->where('quantity' ,'<>', 0)->orderBy('author_id', 'ASC')->paginate(10)
        ], compact('author', 'months'));
        }
      
   
    
    }
    public function sort(Request  $request)
    {
        
        switch($request->sort){
            case 'ASC':
                $author = Author::all();
                $months = MonthHelper::getMonths();       
                return view('royalties.pod', [
                    'pod_transactions' => PodTransaction::where('quantity' ,'<>', 0)->orderBy('book_id','ASC')->paginate(10)
                ], compact('author', 'months'));
            break;
            case 'DESC':
                $author = Author::all();
                $months = MonthHelper::getMonths();
                return view('royalties.pod', [
                    'pod_transactions' => PodTransaction::where('quantity' ,'<>', 0)->orderBy('book_id','DESC')->paginate(10)
                ], compact('author', 'months'));
            break;
            case 'RASC':
                $author = Author::all();
                $months = MonthHelper::getMonths();   
                return view('royalties.pod', [
                    'pod_transactions' => PodTransaction::where('quantity' ,'<>', 0)->orderBy('royalty','ASC')->orderBy('author_id' , 'ASC')->paginate(10)
                ], compact('author', 'months'));
            break;
            case 'RDSC':
                $author = Author::all();
                $months = MonthHelper::getMonths();    
                return view('royalties.pod', [
                    'pod_transactions' => PodTransaction::where('quantity' ,'<>', 0)->orderBy('royalty','DESC')->orderBy('author_id' , 'DESC')->paginate(10)
                ], compact('author', 'months'));
            break;
           

        }
        if($request->months=="all"){
            return redirect()->route('royalty.index');
        
        }else{
            $months = MonthHelper::getMonths();
            $author = Author::all();
            return view('royalties.pod', [
                'pod_transactions' => PodTransaction::where('quantity' ,'<>', 0)->where('month', $request->months)->orderBy('book_id','DESC')->paginate(10)
            ], compact('author', 'months'));
        }

        
    }
   
}

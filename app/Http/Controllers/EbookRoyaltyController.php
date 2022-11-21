<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Helpers\MonthHelper;
use App\Helpers\NameHelper;
use App\Models\EbookTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EbookRoyaltyController extends Controller
{
    public function index(){
        $months = MonthHelper::getMonths();
        $author = Author::get();
        $author = Author::get();
        $ebooktransaction = EbookTransaction ::where('quantity','<>' ,0)->orderBy('author_id', 'DESC')->paginate(10);
        return view('royalties.ebook',['ebook_transactions' => $ebooktransaction,],compact('author','months'));
       
    }
    public function search(Request $request){
        if($request->months == "all"){
            $months = MonthHelper::getMonths();
            $author = Author::all();
            $ebooktransaction = EbookTransaction ::orderBy('author_id', 'ASC')->paginate(10);
            return view('royalties.ebook',['ebook_transactions' => $ebooktransaction,],compact('author','months'));
        }else{
            $months = MonthHelper::getMonths();
            $author = Author::all();
            $ebooktransaction = EbookTransaction ::where('month' , $request->months)->orderBy('author_id', 'ASC')->paginate(10);
            return view('royalties.ebook',['ebook_transactions' => $ebooktransaction,],compact('author','months'));
        }
       
       
        if($request->author_id == 'all'){
            $months = MonthHelper::getMonths();
            $author = Author::all();
            $ebooktransaction = EbookTransaction ::orderBy('author_id', 'ASC')->paginate(10);
            return view('royalties.ebook',['ebook_transactions' => $ebooktransaction,],compact('author','months'));
        }else{
            $months = MonthHelper::getMonths();
            $author = Author::all();
            return view('royalties.ebook',['ebook_transactions' => EbookTransaction::where('author_id',$request->author_id)->orderBy('author_id', 'ASC')->paginate(10)], compact('author', 'months'));
        }
    }
    public function sort(Request $request){
        switch($request->sort){
            case 'ASC':
                $months = MonthHelper::getMonths();
                $author = Author::orderBy('firstname' ,'ASC')->orderBy('lastname' , 'ASC');
                        
                return view('royalties.ebook', [
                    'ebook_transactions' => EbookTransaction::orderBy('book_id','ASC')->paginate(10)
                ], compact('author','months'));
            break;
            case 'DESC':
                $author = Author::orderBy('firstname' ,'DESC')->orderBy('lastname' , 'DESC');
                $months = MonthHelper::getMonths();
                return view('royalties.ebook', [
                    'ebook_transactions' => EbookTransaction::orderBy('book_id','DESC')->paginate(10)
                ], compact('author','months'));
            break;
            case 'EASC':
                $author = Author::orderBy('firstname' ,'ASC')->orderBy('lastname' , 'ASC');
                $months = MonthHelper::getMonths();    
                return view('royalties.ebook', [
                    'ebook_transactions' => EbookTransaction::orderBy('royalty','ASC')->orderBy('author_id' , 'ASC')->paginate(10)
                ], compact('author','months'));
            break;
            case 'EDSC':
                $author = Author::orderBy('firstname' ,'DESC')->orderBy('lastname' , 'DESC');
                $months = MonthHelper::getMonths();      
                return view('royalties.ebook', [
                    'ebook_transactions' => EbookTransaction::orderBy('royalty','DESC')->orderBy('author_id' , 'DESC')->paginate(10)
                ], compact('author','months'));
            break;
            default:
        $months = MonthHelper::getMonths();
        $author = Author::get();
        $author = Author::get();
        $ebooktransaction = EbookTransaction ::orderBy('author_id', 'ASC')->paginate(10);
        return view('royalties.ebook',['ebook_transactions' => $ebooktransaction,],compact('author','months'));

        }
    }
}

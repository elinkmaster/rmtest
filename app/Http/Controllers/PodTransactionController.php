<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Helpers\MonthHelper;
use App\Helpers\NameHelper;
use App\Imports\PodFakesImport;
use App\Imports\PodTransactionsImport;
use App\Jobs\SavePodTransaction;
use App\Models\Author;
use App\Models\Book;
use App\Models\PodFake;
use Illuminate\Http\Request;
use App\Models\PodTransaction;
use Maatwebsite\Excel\Facades\Excel;

class PodTransactionController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        $books = Book::all();
        return view('pod.index', [
            'pod_transactions' => PodTransaction::orderBy('created_at', 'DESC')->paginate(10)
        ], compact('books' ,'authors'));
    }

    public function search(Request $request)
    {
       
        $authors = Author::all();
        $books = Book::all();
        $pod = PodTransaction::where('book_id', $request->book_id)->paginate(10);
       
        if ($request->book_id == 'all') {
            $pod = PodTransaction::orderBy('created_at', 'DESC')->paginate(10);
        }else{
            return view('pod.index', [
                'pod_transactions' => $pod, 'books' => $books , 'authors' => $authors
            ]);
        }
       
        if($request->author_id == 'all'){
            $authors = Author::all();
            $books = Book::all();
            return view('pod.index', [
                'pod_transactions' => PodTransaction::orderBy('created_at', 'DESC')->paginate(10)
            ], compact('books' ,'authors'));
        }
        return view('pod.index', [
            'pod_transactions' => PodTransaction::where('author_id', $request->author_id)->paginate(10), 'books' => $books , 'authors' => $authors
        ]);

    }
    public function sort(Request $request){
        $authors = Author::all();
        $books = Book::all();
        $pod = PodTransaction::where('status', $request->status)->paginate(10);
        if($request->status == 'all'){
            $pod = PodTransaction::orderBy('created_at', 'DESC')->paginate(10);
        }
        return view('pod.index', [
            'pod_transactions' => $pod, 'books' => $books , 'authors' => $authors
        ]);


        
    }
    public function clear(){
       PodTransaction::truncate();
       return back();
    }
    public function create()
    {
        $months = MonthHelper::getMonths();
        $authors = Author::all();
        $books = Book::all();
        return view('pod.create', compact('months', 'authors', 'books'));
    }

    public function importPage()
    {
        return view('pod.import', [
            'months' => MonthHelper::getMonths(),
            'year' => PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'year' => 'required',
            'month' => 'required'
        ]);

        ini_set('max_execution_time', -1);
        Excel::import(new PodTransactionsImport($request->year, $request->month), $request->file('file')->store('temp'));
        ini_set('max_execution_time', 60);
        return back()->with('success', 'Data successfully imported');
    }

    public function store(Request $request)
    {
        $request->validate([
            'author' => 'required',
            'book_title' => 'required',
            'year' => 'required',
            'month' => 'required',
            'flag' => 'required',
            'format' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);
        $x = $request->format;
        $format = strtoupper(substr($x ,-3));
        $instanceid  = "RM".$request->year.$request->month.substr($request->isbn,-4).$format;

        $getRevenue = $request->quantity * $request->price;
        $royalty = number_format($getRevenue * 0.15 ,2);
        $pod = PodTransaction::create([
            'author_id' => $request->author,
            'book_id' => $request->book_title,
            'instance_id'=>$instanceid,
            'year' => $request->year,
            'month' => $request->month,
            'flag' => $request->flag,
            'status' => $request->status,
            'format' => $request->format,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'royalty' => number_format($royalty , 2)
        ]);


        return redirect(route('pod.create'))->with('success', 'Transaction successfully saved');
    }

    public function delete(PodTransaction $pod)
    {
        $pod->delete();
        return redirect()->route('pod.index')->with('success', 'Transaction successfully deleted');
    }

    public function edit(PodTransaction $pod)
    {
        $months = MonthHelper::getMonths();
        $authors = Author::all();
        $books = Book::all();
        return view('pod.edit', compact('pod', 'months', 'authors', 'books'));
    }

    public function update(Request $request, PodTransaction $pod)
    {
        $request->validate([
            'author' => 'required',
            'isbn' => 'required',
            'book_title' => 'required',
            'year' => 'required',
            'month' => 'required',
            'flag' => 'required',
            'format' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);
        $x = $request->format;
        $format = strtoupper(substr($x ,-3));
        $year =$request->year ;
        $month= $request->month;
        $instanceid  = "RM".$year.$month.substr($request->isbn,-3).$format;
        $getRevenue = $request->quantity * $request->price;
        $royalty = number_format($getRevenue * 0.15 ,2);
        $pod->update([
            'author_id' => $request->author,
            'book_id' => $request->book_title,
            'instance_id'=>$instanceid,
            'year' => $request->year,
            'month' => $request->month,
            'flag' => $request->flag,
            'status' => $request->status,
            'format' => $request->format,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'royalty' => number_format($royalty,2)
        ]);

        $book = Book::where('id', $request->book_title)->first();
       if($book){
        $book->update([
            'book_id' => $request->book_title,
            'author_id' => $request->author

        ]);
       }


        return redirect(route('pod.edit', ['pod' => $pod]))->with('success', 'Transaction successfully updated');
    }
}

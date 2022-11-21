<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Helpers\MonthHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\PodTransaction;
use App\Models\RejectedPodTransaction;
use App\View\Components\RejectedPod;
use Illuminate\Http\Request;

class RejectedPodTransactionController extends Controller
{
    public function index(Request $request)
    {
        $months = MonthHelper::getMonths();
        $year =  RejectedPodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
        $books = Book::all();
        $pods = RejectedPodTransaction::orderBy('created_at', 'DESC')->paginate(10);

        if ($request->filter) {
            $pods = RejectedPodTransaction::where('author_name', 'LIKE', "%$request->filter%")->orWhere('book_title', 'LIKE', "%$request->filter%")->orWhere('isbn', $request->filter)->paginate(10);
        }
        
        else if($request->month){
            $pods = RejectedPodTransaction::where('month' , $request->month)->paginate(10);
        }
        return view('rejecteds.pods.index', [
            'pods' => $pods
        ], compact('books' , 'months'));
    }


    public function delete(RejectedPodTransaction $rejected_pod)
    {
        $rejected_pod->delete();
        return back();
    }
    public function clear(){
        RejectedPodTransaction::truncate();
        return back();
    }

    public function edit(RejectedPodTransaction $rejected_pod)
    {
        $authors = Author::all();
        $months = MonthHelper::getMonths();
        return view('rejecteds.pods.edit')
            ->with('pod', $rejected_pod)
            ->with('authors', $authors)
            ->with('months', $months);
    }

    public function update(Request $request, RejectedPodTransaction $rejected_pod)
    {
        $request->validate([
            'author' => 'required',
            'book' => 'required',
            'year' => 'required',
            'market'=> 'required',
            'isbn'=> 'required',
            'month' => 'required',
            'flag' => 'required',
            'format' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        $book = Book::where('title', $request->book)->first();

        if (!$book) {
            $currentDate = Carbon::now()->format('ymd');
            $instanceid ="RM".$currentDate.substr($request->isbn,-4);
            $book = Book::create([

                'title' => $request->book,
                'isbn' => $request->isbn,
                'author_id' =>$request->author,
                'product_id'=> $instanceid
            ]);
        }
        $x = $request->format;
        $format = strtoupper(substr($x ,-3));
        $instanceid  = "RM".$request->year.$request->month.substr($request->isbn,-4).$format;
        $getRevenue = $request->quantity * $request->price;
        $royalty = number_format($getRevenue * 0.15 ,2);
        PodTransaction::create([
            'author_id' => $request->author,
            'book_id' => $book->id,
            'instance_id' =>$instanceid,
            'isbn' => $request->isbn,
            'market' => $request->market,
            'year' => $request->year,
            'month' => $request->month,
            'flag' => $request->flag,
            'format' => $request->format,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'royalty' => number_format($royalty,2),
        ]);

        $rejected_pod->delete();



       return redirect(route('rejecteds-pods.index'));
    }
}

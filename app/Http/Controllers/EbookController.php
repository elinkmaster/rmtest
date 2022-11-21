<?php

namespace App\Http\Controllers;

use App\Helpers\HumanNameFormatterHelper;
use App\Helpers\MonthHelper;
use App\Imports\EbookTransactionsImport;
use App\Models\Author;
use App\Models\Book;
use App\Models\EbookTransaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class EbookController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        $months = MonthHelper::getMonths();
        $year =  EbookTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
        $books = Book::all();
        return view('ebook.index', [
            'ebook_transactions' => EbookTransaction::orderBy('created_at', 'DESC')->paginate(10)
        ], compact('books','authors'));
    }

    public function search(Request $request)
    {
        $authors = Author::all();
        $books = Book::all();
        $ebook = EbookTransaction::where('book_id', $request->book_id)->paginate(10);
       
        if ($request->book_id == 'all') {
            $ebook = EbookTransaction::orderBy('created_at', 'DESC')->paginate(10);
        }else{
            return view('ebook.index', [
                'ebook_transactions' => $ebook, 'books' => $books , 'authors' => $authors
            ]);
        }
       
        if($request->author_id == 'all'){
            $authors = Author::all();
            $books = Book::all();
            return view('ebook.index', [
                'ebook_transactions' => EbookTransaction::orderBy('created_at', 'DESC')->paginate(10)
            ], compact('books' ,'authors'));
        }
        $author= Author::all();
        return view('ebook.index', [
            'ebook_transactions' => EbookTransaction::where('author_id', $request->author_id)->paginate(10), 'books' => $books , 'authors' => $author
        ]);

       
    }
    public function clear(){
        EbookTransaction::truncate();
        return back();
    }
    public function create()
    {
        $months = MonthHelper::getMonths();
        $authors = Author::all();
        $books = Book::all();
        return view('ebook.create', compact('months', 'authors', 'books'));
    }

    public function importPage()
    {
        return view('ebook.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'author' => 'required',
            'book' => 'required',
            'year' => 'required',
            'month' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'proceeds' => 'required'
        ]);

        $ebook = EbookTransaction::create([
            'author_id' => $request->author,
            'book_id' => $request->book,
            'year' => $request->year,
            'month' => $request->month,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'proceeds' => $request->proceeds,
            'royalty' => number_format($request->proceeds / 2),2
        ]);

        return redirect(route('ebook.create'))->with('success', 'Transaction successfully saved');
    }

    public function edit(EbookTransaction $ebook)
    {
        $months = MonthHelper::getMonths();
        $authors = Author::all();
        $books = Book::all();
        return view('ebook.edit', compact('ebook', 'months', 'authors', 'books'));
    }

    public function update(Request $request, EbookTransaction $ebook)
    {
        $request->validate([
            'author' => 'required',
            'book' => 'required',
            'year' => 'required',
            'month' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'proceeds' => 'required'
        ]);

        $ebook->update([
            'author_id' => $request->author,
            'book_id' => $request->book,
            'year' => $request->year,
            'month' => $request->month,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'proceeds' => $request->proceeds,
            'royalty' => $request->proceeds / 2
        ]);


        return redirect(route('ebook.edit', ['ebook' => $ebook]))->with('success', 'Transaction successfully updated');
    }

    public function delete(EbookTransaction $ebook)
    {
        $ebook->delete();

        return redirect()->route('ebook.index')->with('success', 'Transaction successfully deleted');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        ini_set('max_execution_time', -1);

        Excel::import(new EbookTransactionsImport, $request->file('file')->store('temp'));
        ini_set('max_execution_time', 60);
        return back()->with('success', 'Data successfully imported');
    }
}

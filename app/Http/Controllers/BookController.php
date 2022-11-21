<?php

namespace App\Http\Controllers;
use App\Models\Author;
use App\Helpers\NameHelper;
use App\Imports\BooksImport;
use App\Models\PodTransaction;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
class BookController extends Controller
{
    public function index()
    {
        return view('book.index', [
            'books' => Book::paginate(10),
            'bookSearch' => Book::all(),
            'authors' =>Author::all(),
        ]);
    }

    public function search(Request $request)
    {
        
        $book = Book::where('title', $request->title)->paginate(10);
      
        if($request->title == 'all') {
            return redirect(route('book.index'));
        }else{
            return view('book.index', [
                'bookSearch' => Book::all(),
                'books' => $book,
                'authors' => Author :: all(),
            ]);
        }
       
      
       
    }
    public function searchAuthor(Request $request)
    {
        if ($request->author == 'all') {
            return view('book.index', [
                'books' => Book::paginate(10),
                 'bookSearch' => Book::all(),
                'authors' => Author::all(),
         ]);
       }
       $author = Book::where('author_id', $request->author)->paginate(10);
        return view('book.index', [
            'books' => $author,
           'bookSearch' =>Book::all(),
            'authors' => Author:: all(),
        ]);
    }


    public function importPage()
    {
        return view('book.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);
        ini_set('max_execution_time', -1);
        Excel::import(new BooksImport, $request->file('file')->store('temp'));
        ini_set('max_execution_time', 60);
        return back()->with('success', 'Successfully imported data');
    }

    public function create()
    {
        $authors = Author::all();
        return view('book.create',compact('authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
         
            'title' => 'required|unique:books',
            'author' =>'required',
            'isbn'=>'required'
        ]);
        //for pod transactions
        $date =  Carbon::now()->format('m');
        $price = 0.00;
        $quantity = 0;
        $royalty = 0.00;
        $flag = "No";
        $format = "Perfectbound";
        $status = "Unpaid";
        //
        $currentDate = Carbon::now()->format('ymd');
        $request->product_id ="RM".$currentDate.substr($request->isbn,-4);
        $year = "2022";
        $month = Carbon::now()->format('y');
        
                $savebook=Book::create([
                        'isbn' =>$request->isbn,
                        'author_id'=>$request->author,
                        'title' =>$request->title,
                        'product_id'=> $request->product_id

                    ]);
                    if($savebook){
                        $getbook = Book::where('title' ,$request->title)->first();
                        if($getbook){
                            $format = "Perfectbound";
                            $status = "Unpaid";
                            $x = $request->format;
                            $shorty = strtoupper(substr($x ,-3));
                            $podinstance  = "RM".$year.$month.substr($request->isbn,-4).$shorty;
                            PodTransaction::create([
                                'author_id' => $request->author,
                                'book_id' => $getbook->id,
                                'instance_id' => $podinstance,
                                'isbn'=>$request->isbn,
                                'market' =>'Set market',
                                'year' =>'2022',
                                'month'=>$date,
                                'flag'=>$flag,
                                'status' =>$status,
                                'format' =>$format,
                                'quantity'=>$quantity,
                                'price'=>$price,
                                'royalty'=>$royalty
                            ]);
                        }
                    }
       


        return redirect(route('book.create'))->with('success', 'Book successfully added to database');
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        return view('book.edit', compact('book')) ->with('authors', $authors);
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'isbn' => 'required',
            'product_id' => 'required',
            'author'=>'required',
            'title' => 'required|' . Rule::unique('books')->ignore($book->id),
        ]);
        $book->update([
            'author_id' => $request->author,
            'title' => $request->title,
            'isbn' => $request->isbn,
            'product_id' => $request->product_id
        ]);
        $pod = PodTransaction::where('book_id' , $book->id);
        if($pod){
            $pod->update([
                
                'author_id' =>$request->author,
           
                
            ]);
        }
       

        return redirect()->route('book.edit', ['book' => $book])->with('success', 'Book successfully updated to the database');
    }

    public function delete(Book $book)
    {
        $book->delete();

        return redirect()->route('book.index')->with('success', 'Book has been successfully deleted from the database');
    }
}

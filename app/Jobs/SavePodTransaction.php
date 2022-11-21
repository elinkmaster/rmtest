<?php

namespace App\Jobs;

use App\Models\Author;
use App\Models\Book;
use App\Models\PodFake;
use App\Models\PodTransaction;
use App\Models\RejectedAuthor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SavePodTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */


    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $podfakes = PodFake::all();

        foreach($podfakes as $podfake)
        {

            $splitName = explode(", ", $podfake->author);
            if(count($splitName) > 1){
                $newName = $splitName[1]. " " .$splitName[0];
            }else{
                $newName = $podfake->author;
            }
            $author = Author::where('name', 'LIKE',  $newName ."%")->first();
            if($author){
                $book = Book::where('title', $podfake->book)->first();
                $royalty = number_format((float)($podfake->quantity * $podfake->price) * 0.15, 2);
                if($book){
                    PodTransaction::create([
                        'author_id' => $author->id,
                        'book_id' => $book->id,
                        'year' => $podfake->year,
                        'month' => $podfake->month,
                        'flag' => $podfake->flag,
                        'status' => $podfake->status,
                        'format' => $podfake->format,
                        'quantity' => $podfake->quantity,
                        'price' => $podfake->price,
                        'royalty' => $royalty
                    ]);
                }else{
                    $newBook = Book::create([
                        'title' => $podfake->book
                    ]);
                    PodTransaction::create([
                        'author_id' => $author->id,
                        'book_id' => $newBook->id,
                        'year' => $podfake->year,
                        'month' => $podfake->month,
                        'flag' => $podfake->flag,
                        'status' => $podfake->status,
                        'format' => $podfake->format,
                        'quantity' => $podfake->quantity,
                        'price' => $podfake->price,
                        'royalty' => $royalty
                    ]);
                }
            }else{
                RejectedAuthor::create([
                    'author' => $newName
                ]);
            }
            $podfake->delete();
        }
    }
}

<?php

namespace App\Imports;
use Carbon\Carbon;
use App\Models\Book;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


class BooksImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function model(array $row)
    {
        HeadingRowFormatter::default('none');
        $book = Book::where('title', $row['title'] ?? $row['producttitle'])->get();
        if(count($book) == 0 ){
            return new Book([
                'product_id' => $row['alternativeproductid'] ?? '',
                'title' => $row['producttitle'] ?? $row['title'],
                'isbn' =>$row['isbn'] ?? $row['isbn'],
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

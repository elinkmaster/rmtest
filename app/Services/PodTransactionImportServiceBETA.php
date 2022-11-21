<?php

namespace App\Services;

use App\Helpers\HumanNameFormatterHelper;
use App\Helpers\NameHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\PodTransaction;
use App\Models\RejectedPodTransaction;
use Illuminate\Support\Str;

class PodTransactionImportService
{

    private $royalty;

    public function store(array $row, $year, $month): bool
    {

        // check author has comma then separate
        $newName = $row['author'];
        if (str_contains($newName, ",")) {
            $newName = explode(", ", $newName);
            $newName = $newName[1] . " " . $newName[0];
        }


        $formattedName = (new HumanNameFormatterHelper)->parse($newName);

        $author = Author::where('firstname', 'LIKE', NameHelper::normalize($formattedName->FIRSTNAME) . "%")->where('lastname', 'LIKE', NameHelper::normalize($formattedName->LASTNAME) . "%")->first();
        $this->royalty = number_format((float)($row['mtd_quantity'] ?? $row['ptd_quantity'] * $row['list_price']) * 0.15, 2);

        if (!$author) {
            return false;
        }

        $book =  Book::where('title', $row['title'] ?? $row['book'])->first();

        if (!$book) {
            $book = Book::create([
                'title' => $row['title'] ?? $row['book']
            ]);
        }

        $transaction = PodTransaction::where('isbn', $row['isbn'])->where('year', $year)->where('month', $month)->where('market', $row['market'])->first();



        if ($transaction) {
            $transaction->update([
                'author_id' => $author->id,
                'book_id' => $book->id,
                'isbn' => $row['isbn'],
                'market' => $row['market'],
                'year' => $row['year'] ?? $year,
                'month' => $row['mm'] ?? $month,
                'flag' => $row['flag'] ?? 'Yes',
                'status' => $row['status'] ?? '',
                'format' => $row['format'] ?? Str::contains($row['binding_type'], Str::title('perfectbound')) == true ? 'Perfectbound' : Str::title($row['binding_type']),
                'quantity' => $row['mtd_quantity'] ?? $row['ptd_quantity'],
                'price' => $row['list_price'],
                'royalty' => $this->royalty
            ]);

            // return to prevent the next line of code and to indicate that store function has been successful
            return true;
        }

        PodTransaction::create([
            'author_id' => $author->id,
            'book_id' => $book->id,
            'isbn' => $row['isbn'],
            'market' => $row['market'],
            'year' => $row['year'] ?? $year,
            'month' => $row['mm'] ?? $month,
            'flag' => $row['flag'] ?? 'No',
            'status' => $row['status'] ?? '',
            'format' => $row['format'] ?? Str::contains($row['binding_type'], Str::title('perfectbound')) == true ? 'Perfectbound' : Str::title($row['binding_type']),
            'quantity' => $row['mtd_quantity'] ?? $row['ptd_quantity'],
            'price' => $row['list_price'],
            'royalty' => $this->royalty
        ]);

        // return to indicate that store function has been successful
        return true;
    }

    public function reject(array $row, $year, $month)
    {
        $rejectTransaction = RejectedPodTransaction::where('isbn', $row['isbn'])->where('year', $year)->where('month', $month)->where('market', $row['market'])->first();

        if ($rejectTransaction) {
            $rejectTransaction->update([
                'author_name' => $row['author'],
                'book_title' => $row['title'],
                'isbn' => $row['isbn'] ?? $row['isbn'],
                'market' => $row['market'] ?? $row['market'],
                'year' => $row['year'] ?? $year,
                'month' => $row['mm'] ?? $month,
                'flag' => $row['flag'] ?? 'No',
                'status' => $row['status'] ?? '',
                'format' => $row['format'] ?? Str::contains($row['binding_type'], Str::title('perfectbound')) == true ? 'Perfectbound' : Str::title($row['binding_type']),
                'quantity' => $row['mtd_quantity'] ?? $row['ptd_quantity'],
                'price' => $row['list_price'],
                'royalty' => $this->royalty
            ]);

            // prevent to execute the next line of code
            return;
        }

        RejectedPodTransaction::create([
            'author_name' => $row['author'],
            'book_title' => $row['title'],
            'isbn' => $row['isbn'] ?? $row['isbn'],
            'market' => $row['market'] ?? $row['market'],
            'year' => $row['year'] ?? $year,
            'month' => $row['mm'] ?? $month,
            'flag' => $row['flag'] ?? 'No',
            'status' => $row['status'] ?? '',
            'format' => $row['format'] ?? Str::contains($row['binding_type'], Str::title('perfectbound')) == true ? 'Perfectbound' : Str::title($row['binding_type']),
            'quantity' => $row['mtd_quantity'] ?? $row['ptd_quantity'],
            'price' => $row['list_price'],
            'royalty' => $this->royalty
        ]);
    }
}

<?php

namespace App\Imports;

use App\Helpers\HumanNameFormatterHelper;
use App\Helpers\NameHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\EbookTransaction;
use App\Models\RejectedAuthor;
use App\Models\RejectedEbookTransaction;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EbookTransactionsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $name = $row['productauthors'];
        $name = (new HumanNameFormatterHelper)->parse($name);

        $author = Author::where('firstname', 'LIKE', NameHelper::normalize($name->FIRSTNAME) . "%")->where('lastname', 'LIKE', NameHelper::normalize($name->LASTNAME) . "%")->first();
    
        $date = Carbon::parse(Date::excelToDateTimeObject($row['transactiondatetime']));

       // dd($date->month);
        if ($author) {
            $ebookTransaction = EbookTransaction::where('line_item_no', $row['lineitemid'])->where('month', $date->month)->where('year', $date->year)->first();
            $book = Book::where('title', $row['producttitle'])->first();
            if ($ebookTransaction) {
                $ebookTransaction->update([
                    'author_id' => $author->id,
                    'book_id' => $book->id,
                    'year' => $date->year,
                    'month' => $date->month,
                    'class_of_trade' => $row['classoftradesale'],
                    'line_item_no' => $row['lineitemid'],
                    'quantity' => $row['grosssoldquantity'],
                    'price' => $row['unitprice'],
                    'proceeds' => $row['proceedsofsaleduepublisher'],
                    'royalty' => $row['proceedsofsaleduepublisher'] / 2
                ]);
                return;
            }
            if ($book) {
                return new EbookTransaction([
                    'author_id' => $author->id,
                    'book_id' => $book->id,
                    'year' => $date->year,
                    'month' => $date->month,
                    'class_of_trade' => $row['classoftradesale'],
                    'line_item_no' => $row['lineitemid'],
                    'quantity' => $row['grosssoldquantity'],
                    'price' => $row['unitprice'],
                    'proceeds' => $row['proceedsofsaleduepublisher'],
                    'royalty' => number_format($row['proceedsofsaleduepublisher'] / 2),2
                ]);
            }
        } else {
            $rejectedTransaction = RejectedEbookTransaction::where('line_item_no', $row['lineitemid'])->where('month', $date->month)->where('year', $date->year)->first();
            if ($rejectedTransaction) {
                $rejectedTransaction->update([
                    'author_name' => $row['productauthors'],
                    'book_title' => $row['producttitle'],
                    'year' => $date->year,
                    'month' => $date->month,
                    'class_of_trade' => $row['classoftradesale'],
                    'line_item_no' => $row['lineitemid'],
                    'quantity' => $row['grosssoldquantity'],
                    'price' => $row['unitprice'],
                    'proceeds' => $row['proceedsofsaleduepublisher'],
                    'royalty' => number_format($row['proceedsofsaleduepublisher'] / 2),2
                ]);
                return;
            }

            RejectedEbookTransaction::create([
                'author_name' => $row['productauthors'],
                'book_title' => $row['producttitle'],
                'year' => $date->year,
                'month' => $date->month,
                'class_of_trade' => $row['classoftradesale'],
                'line_item_no' => $row['lineitemid'],
                'quantity' => $row['grosssoldquantity'],
                'price' => $row['unitprice'],
                'proceeds' => $row['proceedsofsaleduepublisher'],
                'royalty' => number_format($row['proceedsofsaleduepublisher'] / 2),2
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}

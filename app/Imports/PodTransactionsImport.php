<?php

namespace App\Imports;
use Carbon\Carbon;
use App\Helpers\DatabaseDataValidatorHelper;
use App\Helpers\HumanNameFormatterHelper;
use App\Helpers\NameHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\PodTransaction;
use App\Models\RejectedAuthor;
use App\Models\RejectedPodTransaction;
use App\Services\PodTransactionImportService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class PodTransactionsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $year, $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function model(array $row)
    {
        if (!empty($row['author'])) {
            $podTransactionService = new PodTransactionImportService();
            $response = $podTransactionService->store($row, $this->year, $this->month);

            // if response value is false trigger this
            if (!$response) {
                $podTransactionService->reject($row, $this->year, $this->month);
            }
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

    public function batchSize(): int
    {
        return 1000;
    }
}

<?php

namespace App\Imports;

use App\Helpers\HumanNameFormatterHelper;
use App\Models\Author;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AuthorsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $formattedName = (new HumanNameFormatterHelper)->parse($row['name'] ?? $row['author']);
        $author = Author::where('firstname', $formattedName->FIRSTNAME)->where('lastname', $formattedName->LASTNAME)->get();
        if (count($author) == 0) {
            return new Author([
                'title' => $formattedName->TITLE,
                'firstname' => $formattedName->FIRSTNAME,
                'middle_initial' => $formattedName->MIDDLEINITIAL,
                'lastname' => $formattedName->LASTNAME,
                'suffix' => $formattedName->SUFFIX,
                'uid' => $row['id'] ?? 'null',
                'email' => $row['email_address'],
                'contact_number' => $row['home_phone'],
                'address' => $row['address']
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}

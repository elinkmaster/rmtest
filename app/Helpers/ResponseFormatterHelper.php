<?php

namespace App\Helpers;

class ResponseFormatterHelper {

    public static function generateResponseOnlyBook(...$data)
    {
        $response = [];
        foreach($data as $datum)
        {
            foreach($datum as $transaction)
            {
                if($transaction){
                    if(!in_array(['book_id' => $transaction->book_id, 'book_title' => $transaction->book->title], $response))
                    {
                        array_push($response, ['book_id' => $transaction->book_id, 'book_title' => $transaction->book->title]);
                    }
                }
            }
        }

        return $response;
    }

    public static function generateResponseOnlyYear(...$data)
    {
        $response = [];
        foreach($data as $datum)
        {
            foreach($datum as $transaction)
            {
                if($transaction){
                    if(!in_array($transaction->year, $response))
                    {
                        array_push($response, $transaction->year);
                    }
                }
            }
        }

        return $response;
    }

}

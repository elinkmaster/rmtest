<?php

namespace App\Helpers;

use App\Models\Author;

class DatabaseDataValidatorHelper
{
    public static function findNameInAuthor($name)
    {
        $findName = "";

        for($x = 0; $x < count($name); $x++){
            $findName = "%" . $name[$x] . "%";
            $author = Author::where('name', 'LIKE', $findName)->get();
            if(count($author) == 1){
                return $author[0];
            }
        }

        for($x = (count($name) - 1); $x > 0; $x--){
            $findName = "%" . $name[$x] . "%";
            $author = Author::where('name', 'LIKE', $findName)->get();
            if(count($author) == 1){
                return $author[0];
            }
        }

        for($x = 0; $x < count($name); $x++){
            $findName .= "%" . $name[$x] . "%";
            $author = Author::where('name', 'LIKE', $findName)->get();
            if(count($author) == 1){
                return $author[0];
            }
        }

        for($x = (count($name) - 1); $x > 0; $x--){
            $findName .= "%" . $name[$x] . "%";
            $author = Author::where('name', 'LIKE', $findName)->get();
            if(count($author) == 1){
                return $author[0];
            }
        }

    }
}

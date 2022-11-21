<?php

namespace App\Helpers;

class NameHelper{
    public function parse($name)
    {
       $name = $this->normalize($name);
       $segments = explode(',', $name);

       return $segments;
    }

    public static function normalize($name)
    {
        $whitespace = "\r\n\t";
        $name = trim($name);

        return preg_replace('/[' . preg_quote($whitespace) .']+/', ' ', $name);
    }

}

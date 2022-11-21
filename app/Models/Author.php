<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'firstname',
        'middle_initial',
        'lastname',
        'suffix',
        'email',
        'contact_number',
        'address',
    ];

    public function pod_transcations()
    {
        return $this->hasMany(PodTransaction::class);
    }

    public function ebook_transcations()
    {
        return $this->hasMany(EbookTransaction::class);
    }

    public function getFullName()
    {
        return $this->title . " " . $this->firstname . " " . $this->middle_initial . " " . $this->lastname . " " . $this->suffix;
    }

    public function getFullName2()
    {
        return $this->lastname . ", " . $this->firstname;
    }
}

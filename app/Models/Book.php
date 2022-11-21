<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'isbn',
        'author_id'
    ];

    public function pod_transcations()
    {
        return $this->hasMany(PodTransaction::class);
    }

    public function ebook_transcations()
    {
        return $this->hasMany(EbookTransaction::class);
    }
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}

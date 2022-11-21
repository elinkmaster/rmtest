<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedPodTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'book_title',
        'instance_id',
        'isbn',
        'market',
        'year',
        'month',
        'flag',
        'status',
        'format',
        'quantity',
        'price',
        'royalty'
    ];
}

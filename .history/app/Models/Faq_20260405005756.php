<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category1_id',
        'category2_id',
        'question',
        'answer',
        'note',
        'pdf',
        'url',
        'sort_order',
        'is_visible',
    ];
}
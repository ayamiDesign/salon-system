<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaqHistories extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'faq_id',
        'category1_id',
        'category2_id',
        'question',
        'answer',
        'note',
        'pdf',
        'pdf_original_name',
        'url',
        'change_summary',
        'sort_order',
        'is_visible',
    ];
}
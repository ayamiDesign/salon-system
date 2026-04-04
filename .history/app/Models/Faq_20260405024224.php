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

    public function scopeCategoryMatch($query, $id)
    {
        return $query->where(function ($q) use ($id) {
            $q->where('category1_id', $id)
            ->orWhere('category2_id', $id);
        });
    }
}
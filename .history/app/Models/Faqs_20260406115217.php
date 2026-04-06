<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faqs extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category1_id',
        'category2_id',
        'question',
        'answer',
        'note',
        'pdf',
        'pdf_original_name',
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

    public function scopeSearch($category, $keyword)
    {
        return Faqs::where('category_id', $category)
            ->where('question', 'like', "%{$keyword}%")
            ->orwhere('answer', 'like', "%{$keyword}%")
            ->orwhere('note', 'like', "%{$keyword}%")
            ->orderBy('sort_order')
            ->get();
    }
}
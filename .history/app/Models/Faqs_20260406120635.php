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

    public function scopeSearch($query, $category, $keyword)
    {
        $query = Faqs::query();

        if ($category !== '0') {
            $query->where('category_id', $category);
        }

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('question', 'like', "%{$keyword}%")
                    ->orWhere('answer', 'like', "%{$keyword}%")
                    ->orWhere('note', 'like', "%{$keyword}%");
            });
        }

        $faqs = $query->orderBy('sort_order')->get();   
        return $faqs;
    }
}
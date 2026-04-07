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

    public function scopeSearch($query, $searchCategory, $searchKeyword)
    {
        return $query
            ->when($searchCategory !== '0', function ($q) use ($searchCategory) {
                $q->where(function ($sub) use ($searchCategory) {
                    $sub->where('category1_id', $searchCategory)
                        ->orWhere('category2_id', $searchCategory);
                });
            })
            ->when(!empty($searchKeyword), function ($q) use ($searchKeyword) {
                $q->where(function ($sub) use ($searchKeyword) {
                    $sub->where('question', 'like', "%{$searchKeyword}%")
                        ->orWhere('answer', 'like', "%{$searchKeyword}%")
                        ->orWhere('note', 'like', "%{$searchKeyword}%");
                });
            })
            ->orderBy('sort_order');
    }
}
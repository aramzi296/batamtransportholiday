<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'question_en',
        'answer',
        'answer_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope untuk FAQ yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Get question based on locale
     */
    public function getQuestionForLocale($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'en' && $this->question_en) {
            return $this->question_en;
        }
        return $this->question;
    }

    /**
     * Get answer based on locale
     */
    public function getAnswerForLocale($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'en' && $this->answer_en) {
            return $this->answer_en;
        }
        return $this->answer;
    }
}








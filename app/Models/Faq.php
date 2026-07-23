<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'question_bn',
        'question_ar',
        'answer',
        'answer_bn',
        'answer_ar',
        'category',
        'service_type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const CATEGORY_GENERAL = 'general';
    public const CATEGORY_FLIGHT = 'flight';
    public const CATEGORY_UMRAH = 'umrah';
    public const CATEGORY_VISA = 'visa';
    public const CATEGORY_CARGO = 'cargo';
    public const CATEGORY_PAYMENT = 'payment';
    public const CATEGORY_INVESTOR = 'investor';

    public const CATEGORIES = [
        self::CATEGORY_GENERAL => 'General',
        self::CATEGORY_FLIGHT => 'Flight',
        self::CATEGORY_UMRAH => 'Umrah',
        self::CATEGORY_VISA => 'Visa',
        self::CATEGORY_CARGO => 'Cargo',
        self::CATEGORY_PAYMENT => 'Payment',
        self::CATEGORY_INVESTOR => 'Investor',
    ];

    public function getQuestionAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->question_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->question_ar ?: $value;
        }
        return $value;
    }

    public function getAnswerAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->answer_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->answer_ar ?: $value;
        }
        return $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByService($query, string $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }
}

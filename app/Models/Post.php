<?php

namespace App\Models;

use App\Enums\PostCurrencySalaryEnum;
use App\Enums\PostStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'city',
        'company_id',
        'currency_salary',
        'district',
        'end_date',
        'is_parttime',
        'job_title',
        'max_salary',
        'min_salary',
        'number_applicants',
        'pinned',
        'remotable',
        'requirement',
        'start_date',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('job_title')
            ->saveSlugsTo('slug');
    }

    protected static function booted()
    {
        static::creating(function ($object) {
            $object->user_id = user()->id;
            $object->status = 1;
        });
    }

    public function getCurrencySalaryCodeAttribute(): string
    {
        return PostCurrencySalaryEnum::getKey($this->currency_salary);
    }

    public function getStatusNameAttribute(): string
    {
        return PostStatusEnum::getKey($this->status);
    }

    public function getLocationAttribute()
    {
        if(!empty($this->district)) {
            return $this->district . ' - ' . $this->city;
        }
    }

    public function languages(): MorphToMany
    {
        return $this->morphToMany(
            Language::class,
            'object',
            ObjectLanguage::class,
            'object_id',
            'language_id',
        );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}

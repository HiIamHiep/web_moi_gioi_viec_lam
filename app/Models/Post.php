<?php

namespace App\Models;

use App\Enums\PostCurrencySalaryEnum;
use App\Enums\PostStatusEnum;
use App\Enums\SystemCacheKeyEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;

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

    protected static function booted()
    {
        static::creating(function ($object) {
            $object->user_id = user()->id;
            $object->status = 1;
        });
        static::saved(function ($object) {
            $city = $object->city;
            $arr = explode(', ', $city);
            $arrCity = getAndCachePostCities();
            foreach ($arr as $item){
                if(in_array($item, $arrCity)){
                    continue;
                }
                $arrCity[] = $item;
            }
            cache()->put(SystemCacheKeyEnum::POST_CITIES, $arrCity);
        });

//        @todo @pobby tạo thêm trường hợp người dùng delete hết bài đăng của công ty đã có trong db
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('job_title')
            ->saveSlugsTo('slug');
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

    public function getCurrencySalaryCodeAttribute(): string
    {
        return PostCurrencySalaryEnum::getKey($this->currency_salary);
    }

    public function getStatusNameAttribute(): string
    {
        return PostStatusEnum::getKey($this->status);
    }

    public function getLocationAttribute(): ?string
    {
        if(!empty($this->district)) {
            return $this->district . ' - ' . $this->city;
        }
        return $this->city;
    }

    public function getSalaryAttribute(): ?string
    {
        $val = $this->currency_salary;
        $key = PostCurrencySalaryEnum::getKey($val);
        $locale = PostCurrencySalaryEnum::getLocaleByVal($val);
        $format = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        $rate = Config::getBykey($key);

        if(!is_null($this->min_salary)){
            $salary = $this->min_salary * $rate;
            $minSalary = $format->formatCurrency($salary, $key);
        }

        if(!is_null($this->max_salary)){
            $salary = $this->max_salary * $rate;
            $maxSalary = $format->formatCurrency($salary, $key);
        }

        if(!empty($minSalary) && !empty($maxSalary)){
            return $minSalary . ' - ' . $maxSalary;
        }

        if (!empty($minSalary)){
            return __('frontpage.from_salary') .  ' ' .  $minSalary;
        }

        if (!empty($maxSalary)){
            return __('frontpage.to_salary') . ' ' . $maxSalary;
        }

        return '' ;
    }

}

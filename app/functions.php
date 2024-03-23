<?php

use App\Enums\SystemCacheKeyEnum;
use App\Enums\UserRoleEnum;
use App\Models\Language;
use App\Models\Post;

if (!function_exists('getRoleByKey')){
    function getRoleByKey($key): string
    {
        return strtolower(UserRoleEnum::getKeys((int)$key)[0]);
    }
}

if (!function_exists('user')){
    function user(): ?object
    {
        return auth()->user();
    }
}

if (!function_exists('isSuperAdmin')){
    function isSuperAdmin(): bool
    {
        return user() && user()->role === UserRoleEnum::SUPER_ADMIN;
    }
}

if (!function_exists('isAdmin')){
    function isAdmin(): bool
    {
        return user() && user()->role === UserRoleEnum::ADMIN;
    }
}

if (!function_exists('getPostCities')){
    function getAndCachePostCities(): array
    {
        return cache()->remember(SystemCacheKeyEnum::POST_CITIES, 86400 * 30, function () {
            $cities = Post::query()->pluck('city');
            $arrCity = [];
            foreach ($cities as $city) {
                if (empty($city)){
                    continue;
                }
                $arr = explode(', ', $city);
                foreach ($arr as $item){
                    if (empty($item)){
                        continue;
                    }
                    if(in_array($item, $arrCity)){
                        continue;
                    }
                    $arrCity[] = $item;
                }
            }
            return $arrCity;
        });
    }
}

if (!function_exists('getAndCacheLanguages')){
    function getAndCacheLanguages(): array
    {
        return cache()->remember(SystemCacheKeyEnum::LANGUAGES, 86400 * 30, function () {
            $languages = Language::query()->pluck('name');
            $arrLanguage = [];
            foreach ($languages as $language) {
                if (empty($language)){
                    continue;
                }
                if(in_array($language, $arrLanguage)) {
                    continue;
                }

                $arrLanguage[] = $language;
            }
            return $arrLanguage;
        });
    }
}





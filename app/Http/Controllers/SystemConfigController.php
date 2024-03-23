<?php

namespace App\Http\Controllers;

use App\Enums\CompanyCountryEnum;
use App\Enums\PostCurrencySalaryEnum;
use App\Enums\PostLevelEnum;
use App\Enums\PostRemotableEnum;
use App\Models\Language;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{
    public static function getAndCache(): array
    {
        return cache()->remember('configs', 24*60*60, function (){
            $arr = [];

            $arr['levels'] = PostLevelEnum::asArray();
            $arr['currencies'] = PostCurrencySalaryEnum::asArray();
            $arr['countries'] = CompanyCountryEnum::asArray();
            $arr['languages'] = Language::query()->get();
            $arr['remotables'] = PostRemotableEnum::getArrWithLowerKey();

            return $arr;

        });


    }
}

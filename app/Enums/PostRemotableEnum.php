<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use Illuminate\Support\Str;

final class PostRemotableEnum extends Enum
{
    public const ALL = 0;
    public const OFFICE_ONLY = 2;
    public const REMOTE_ONLY = 1;
    public const HYBRID = 3;

    public static function getArrWithLowerKey()
    {
        $arr =[];
        $data = self::asArray();
        foreach ($data as $key => $value) {
            $index = Str::lower($key);
            $arr[$index] = $value;
        }

        return $arr;
    }

    public static function getArrWithoutAll()
    {
        $arr = self::asArray();
        array_shift($arr);

        return $arr;
    }

}

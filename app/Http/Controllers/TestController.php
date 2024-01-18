<?php

namespace App\Http\Controllers;

use App\Enums\PostCurrencySalaryEnum;
use App\Enums\PostStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use NumberFormatter;

class TestController extends Controller
{
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index()
    {
        $val = PostCurrencySalaryEnum::KRW;
        $key = PostCurrencySalaryEnum::getKey($val);
        $locale = PostCurrencySalaryEnum::getLocaleByVal($val);

        $format = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $format->formatCurrency('123123', $key);


    }

}

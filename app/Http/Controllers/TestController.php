<?php

namespace App\Http\Controllers;

use App\Enums\PostStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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
        $companyName = 'Da cap';
        $language = '';
        $city = 'HN';
        $link = 'abc';

        if(!empty($companyName)) {
            $companyId = Company::query()
                ->firstOrCreate([
                    'name' => $companyName,
                ],[
                    'city' => $city,
                    'country' => 'Vietnam',
                ])->id;
        } else {
            $companyId = null;
        }


        $post = Post::query()
            ->create([
                'job_title' => $language,
                'company_id' => $companyId,
                'city' => $city,
                'status' => PostStatusEnum::ADMIN_APPROVED,
            ]);
    }

}

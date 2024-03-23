<?php

namespace App\Http\Controllers\Applicant;

use App\Enums\PostRemotableEnum;
use App\Enums\PostStatusEnum;
use App\Enums\SystemCacheKeyEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Applicant\HomePage\IndexRequest;
use App\Models\Company;
use App\Models\Config;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public function index(IndexRequest $request)
    {

        $searchCities = $request->input('cities', []);

        $arrCity = getAndCachePostCities();
        $arrLanguage = getAndCacheLanguages();
        $configs = Config::getAndCache(0);
        $minSalary = $request->input('min_salary', $configs['filter_min_salary']);
        $maxSalary = $request->input('max_salary', $configs['filter_max_salary']);
        $remotable = $request->input('remotable');
        $searchCanParttime = $request->boolean('can_parttime');
        $searchLanguages = $request->input('languages', []);
        $searchJobTitle = $request->input('q');

        $filters = [];

        if (!empty($searchCities)) {
            $filters['cities'] = $searchCities;
        }
        if (!empty($minSalary)) {
            $filters['min_salary'] = $minSalary;
        }
        if (!empty($maxSalary)) {
            $filters['max_salary'] = $maxSalary;
        }

        if (!empty($remotable)){
            $filters['remotable'] = $remotable;
        }

        if (!empty($searchCanParttime)){
            $filters['can_parttime'] = $searchCanParttime;
        }

        if (!empty($searchLanguages)){
            $filters['languages'] = $searchLanguages;
        }

        if (!empty($searchJobTitle)){
            $filters['job_title'] = $searchJobTitle;
        }

        $posts = Post::query()
            ->IndexHomePage($filters)
            ->paginate();

        $filterPostRemotable = PostRemotableEnum::getArrWithLowerKey();

        return view('applicant.index', [
            'posts' => $posts,
            'arrCity' => $arrCity,
            'arrLanguage' => $arrLanguage,
            'searchCities' => $searchCities,
            'configs' => $configs,
            'minSalary' => $minSalary,
            'maxSalary' => $maxSalary,
            'remotable' => $remotable,
            'filterPostRemotable' => $filterPostRemotable,
            'searchCanParttime' => $searchCanParttime,
            'searchLanguages' => $searchLanguages,
        ]);
    }

    public function show($postId)
    {
        $post =  Post::query()
            ->with([
                'file',
                'company' => function ($q) {
                    return $q->select([
                        'id',
                        'name',
                    ]);
                },
            ])
            ->approved()
            ->findOrFail($postId);

        $title = $post->job_title;

        return view('applicant.show', [
            'post' => $post,
            'title' => $title,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Applicant;

use App\Enums\PostRemotableEnum;
use App\Enums\PostStatusEnum;
use App\Enums\SystemCacheKeyEnum;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Config;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public function index(Request $request)
    {
        $searchCities = $request->get('cities', []);

        $arrCity = getAndCachePostCities();
        $configs = Config::getAndCache(0);
        $minSalary = $request->get('min_salary', $configs['filter_min_salary']);
        $maxSalary = $request->get('max_salary', $configs['filter_max_salary']);

        $query = Post::query()
            ->with([
                'languages',
                'company' => function ($q) {
                    return $q->select([
                        'id',
                        'name',
                        'logo',
                    ]);
                },
            ])
            ->approved()
            ->orderByDesc('pinned')
            ->orderByDesc('id');
        // Cách select theo group where or where tiện
        if (!empty($searchCities)) {
            $query->where(function ($q) use ($searchCities) {
                foreach ($searchCities as $searchCity) {
                    $q->orWhere('city', 'like', '%'.$searchCity.'%');
                }
                $q->orWhereNull('city');

                return $q;
            });
        }

        if (!empty($minSalary)) {
            $query->where(function ($q) use ($minSalary) {
                $q->orWhere('min_salary', '>=', $minSalary);
                $q->orWhereNull('min_salary');

            });
        }

        if (!empty($maxSalary)) {
            $query->where(function ($q) use ($maxSalary) {
                $q->orWhere('max_salary', '<=', $maxSalary);
                $q->orWhereNull('max_salary');
            });
        }

        $remotable = $request->get('remotable');

        if (!empty($remotable)){
            $query->where('remotable', $remotable);
        }

        $posts = $query->paginate();

        $filterPostRemotable = PostRemotableEnum::getArrWithLowerKey();

        return view('applicant.index', [
            'posts' => $posts,
            'arrCity' => $arrCity,
            'searchCities' => $searchCities,
            'configs' => $configs,
            'minSalary' => $minSalary,
            'maxSalary' => $maxSalary,
            'remotable' => $remotable,
            'filterPostRemotable' => $filterPostRemotable,
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


        return view('applicant.show', [
            'post' => $post,
        ]);
    }
}

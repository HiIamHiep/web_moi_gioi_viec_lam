<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CheckSlugRequest;
use App\Http\Requests\Post\GenerateSlugRequest;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class PostController extends Controller
{
    private object $model;
    private string $table;

    use ResponseTrait;

    public function __construct()
    {
        $this->model = Post::query();
    }

    public function index(): JsonResponse
    {
        $data = $this->model->paginate();
        foreach ($data as $each) {
            $each->currency_salary = $each->currency_salary_code;
            $each->status = $each->status_name;
        }

        $arr['data'] = $data->getCollection();
        $arr['pagination'] = $data->linkCollection();

        return $this->successResponse($arr);
    }

    public function generateSlug(GenerateSlugRequest $request): JsonResponse
    {
        try {
            $title = $request->get('title');
            $replaced = Str::replace(',', ' ', $title);
            $slug =  Str::slug($replaced, '-');

            $dem = Post::query()
                ->where('slug', $slug)
                ->count();

            if($dem !== 0) {
                $slug .= '-' . $dem;
            }

            return $this->successResponse($slug);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function checkSlug(CheckSlugRequest $request): JsonResponse
    {
        return $this->successResponse();
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    use ResponseTrait;

    private object $model;

    public function __construct()
    {
        $this->model = Language::query();
    }

    public function index(Request $request): JsonResponse
    {
        $query = $this->model;

        if(!empty($request->get('q'))){
            $query = $this->model
                ->where('name', 'like', '%' . $request->get('q') . '%');
        }

        $data = $query->get();

        return $this->successResponse($data);
    }

}

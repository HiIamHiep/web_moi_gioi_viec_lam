<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private object $model;
    private string $table;

    use ResponseTrait;

    public function __construct()
    {
        $this->model = User::query();
    }

    public function index(Request $request)
    {


        $search = $request->input('q');

        $query = $this->model
            ->latest();

        if (!empty($search)) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $data = $query->simplePaginate();

        return $this->successResponse($data);
    }



}

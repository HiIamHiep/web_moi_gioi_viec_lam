<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Http\Requests\Company\StoreRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Throwable;

class CompanyController extends Controller
{
    use ResponseTrait;

    private object $model;

    public function __construct()
    {
        $this->model = Company::query();

    }
    public function store(StoreRequest $request)
    {
//        name folder save
//        $storage_name = 'company_logo/' . auth()->id();

        try {
            $arr = $request->validated();

            $arr['logo'] = optional($request->get('file'))->storage('company_logo');

            $this->model->create($arr);

            return $this->successResponse();
        } catch (Throwable $e) {

            $message = '';
            if ($e->getCode() === '23000'){
                $message = "Duplicate company name";
            }
            return $this->errorResponse($message);
        }

    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    private object $model;
    private string $table;

    use ResponseTrait;

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();
        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user()->id;

        $selectedRole    = $request->input('role');
        $selectedCity    = $request->input('city');
        $selectedCompany = $request->input('company');

        $query = $this->model->clone()
            ->with('company:id,name')
            ->latest();

        if (!is_null($selectedRole)) {
            $query->where('role', $selectedRole);
        }
        if (!is_null($selectedCity)) {
            $query->where('city', $selectedCity);
        }
        if (!is_null($selectedCompany)) {
            $query->whereHas('company', function ($q) use ($selectedCompany) {
                return $q->where('id', $selectedCompany);
            });
        }

        $query->where('id', '!=', $currentUser);

        $data = $query
            ->paginate()
            ->appends($request->all());


        $roles = UserRoleEnum::asArray();

        $companies = Company::query()
            ->get([
                'id',
                'name',
            ]);

        $cities = $this->model->clone()
            ->distinct()
            ->limit(10)
            ->whereNotNull('city')
            ->pluck('city');

        return view("admin.$this->table.index", [
            'data'            => $data,
            'roles'           => $roles,
            'cities'          => $cities,
            'companies'       => $companies,
            'selectedRole'    => $selectedRole,
            'selectedCity'    => $selectedCity,
            'selectedCompany' => $selectedCompany,
        ]);
    }

    public function getUserWithAjax(Request $request)
    {
        // Where role cách 1 ( nhanh )
        $query = $this->model
            ->with('company:id,name')
            ->latest();

        $selectedRole = $request->get('role');
        $selectedCity = $request->get('city');
        $selectedCompany = $request->get('company');
        $searchUserName = $request->input('q');

        if (!is_null($searchUserName)) {
            $query->where('name', 'like', '%'. $searchUserName .'%');
        }

        if (!is_null($selectedRole)) {
            $query->where('role', $selectedRole);
        }
        if (!is_null($selectedCity)) {
            $query->where('city', $selectedCity);
        }
        if (!is_null($selectedCompany)) {
            $query->whereHas('company', function ($q) use ($selectedCompany) {
                return $q->where('id', $selectedCompany);
            });
        }
        $data = $query->paginate();

        return $this->successResponse($data);
    }

    public function show()
    {

    }

    public function destroy($userId)
    {
        User::destroy($userId);

        return redirect()->back();
    }
}

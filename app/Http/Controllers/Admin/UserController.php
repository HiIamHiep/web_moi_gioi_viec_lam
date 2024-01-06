<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class UserController extends Controller
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

    public function index(Request $request)
    {

        // Where role cách 1 ( nhanh )
        $query = $this->model->clone()
            ->with('company:id,name')
            ->latest();

        $selectedRole = $request->get('role');
        $selectedCity = $request->get('city');
        $selectedCompany = $request->get('company');

        if (!empty($selectedRole) && $selectedRole !== 'All') {
            $query->where('role', $selectedRole);
        }

        if (!empty($selectedCity) && $selectedCity !== 'All') {
            $query->where('city', $selectedCity);
        }

        if (!empty($selectedCompany) && $selectedCompany !== 'All') {
            $query->whereHas('company', function ($q) use ($selectedCompany) {
                return $q->where('id', $selectedCompany);
            });
        }

        $data = $query->paginate();

        //Where role cách 2 ( chậm )
        /*
         $query = $this->model
            ->when($request->has('role'), function ($q) use ($request) {
                return $q->where('role', $request->get('role'));
            })
            ->with('company:id,name')
            ->latest()
            ->paginate();
         */

        $roles = UserRoleEnum::asArray();

        $cities = $this->model->clone()
            ->distinct()
            ->limit(10)
            ->pluck('city');

        $companies = Company::query()
            ->select([
                'id',
                'name',
            ])
            ->get();

        return view("admin.$this->table.index", [
            'data' => $data,
            'roles' => $roles,
            'cities' => $cities,
            'companies' => $companies,
            'selectedRole' => $selectedRole,
            'selectedCity' => $selectedCity,
            'selectedCompany' => $selectedCompany,
        ]);
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

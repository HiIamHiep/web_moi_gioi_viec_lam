<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {

    }

    public function index() {
        return view('admin.dashboard.index');
    }

}

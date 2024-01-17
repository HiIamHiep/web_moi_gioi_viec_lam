<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ObjectLanguageTypeEnum;
use App\Enums\PostRemotableEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Http\Controllers\SystemConfigController;
use App\Http\Requests\Post\StoreRequest;
use App\Imports\PostImport;
use App\Models\Company;
use App\Models\Language;
use App\Models\ObjectLanguage;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class PostController extends Controller
{

    use ResponseTrait;

    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Post::query();
        $this->table = (new Post())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index()
    {
        return view("admin.$this->table.index");
    }

    public function create()
    {
        $configs = SystemConfigController::getAndCache();

        return view("admin.$this->table.create", [
            'currencies' => $configs['currencies'],
            'countries' => $configs['countries'],
        ]);
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $arr = $request->only([
                'city',
                'company_id',
                'currency_salary',
                'district',
                'end_date',
                'is_parttime',
                'job_title',
                'max_salary',
                'min_salary',
                'number_applicants',
                'pinned',
                'remotable',
                'requirement',
                'start_date',
                'status',
            ]);

            $companyName = $request->get('company');

            if(!empty($companyName)){
                $arr['company_id'] = Company::query()
                    ->firstOrCreate(['name' => $companyName])
                    ->id;
            }

            if($request->has('remotables')){
                $remotables = $request->get('remotables');
                if(!empty($remotables['remote']) && !empty($remotables['office'])){
                    $arr['remotable'] = PostRemotableEnum::DYNAMIC;
                } else if (!empty($remotables['remote'])){
                    $arr['remotable'] = PostRemotableEnum::REMOTE_ONLY;
                } else {
                    $arr['remotable'] = PostRemotableEnum::OFFICE_ONLY;
                }
            }

            if($request->has('can_parttime')) {
                $arr['can_parttime'] = 1;
            }

            $languages = $request->get('languages');

            $post = $this->model->create($arr);

            foreach ($languages as $language) {
                $language = Language::query()->firstOrCreate(['name' => $language]);
                ObjectLanguage::query()
                    ->create([
                        'object_id' => $post->id,
                        'language_id' => $language->id,
                        'object_type' => Post::class,
                    ]);
            }

            DB::commit();

            return $this->successResponse();
        } catch (Throwable $e) {
            DB::rollback();
            return $this->errorResponse($e);
        }
    }

    public function importCsv(Request $request)
    {
        try {
            Excel::import(new PostImport, $request->file('file'));
            return $this->successResponse();

        } catch (Throwable $e) {
            return $this->errorResponse();
        }
    }
}

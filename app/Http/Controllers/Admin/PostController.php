<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ObjectLanguageTypeEnum;
use App\Enums\PostCurrencySalaryEnum;
use App\Enums\PostLevelEnum;
use App\Enums\PostRemotableEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Http\Controllers\SystemConfigController;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Imports\PostImport;
use App\Models\Company;
use App\Models\File;
use App\Models\Language;
use App\Models\ObjectLanguage;
use App\Models\Post;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
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
        $levels = SystemConfigController::getAndCache()['levels'];

        return view("admin.$this->table.index", [
            'levels' => $levels,
        ]);
    }

    public function show($postId)
    {
        dd(1);
    }

    public function create()
    {
        $configs = SystemConfigController::getAndCache();
        $remotables = PostRemotableEnum::getArrWithoutAll();

        return view("admin.$this->table.create", [
            'currencies' => $configs['currencies'],
            'countries' => $configs['countries'],
            'remotables' => $remotables,
        ]);
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $arr = $request->validated();

            $companyName = $request->get('company');

            if(!empty($companyName)){
                $arr['company_id'] = Company::query()
                    ->firstOrCreate(['name' => $companyName])
                    ->id;
            }

            if($request->has('remotables')){
                $arr['remotable'] = $request->get('remotables');
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

    public function edit($postId) {
        $post = $this->model->findOrFail($postId);
        $configs = SystemConfigController::getAndCache();
        $remotables = PostRemotableEnum::getArrWithoutAll();
        $levels = PostLevelEnum::asArray();

        $arr['levels'] = explode(", ",$post->levels);

        $languages = $post->languages->toArray();
        $arrIdLanguages = [];
        foreach ($languages as $language) {
            $arrIdLanguages[] = $language['id'];
        }

        $stringIdLanguages = implode(", ",$arrIdLanguages);

        $file = optional($post->file);

        return view("admin.$this->table.edit",[
            'post' => $post,
            'currencies' => $configs['currencies'],
            'countries' => $configs['countries'],
            'remotables' => $remotables,
            'levels' => $levels,
            'arr' => $arr,
            'file' => $file,
            'stringIdLanguages' => $stringIdLanguages,
        ]);
    }

    public function update(UpdateRequest $request) {
        try {
            DB::beginTransaction();

            $arr = $request->validated();

            $companyName = $request->get('company');

            if(!empty($companyName)){
                $arr['company_id'] = Company::query()
                    ->firstOrCreate(['name' => $companyName])
                    ->id;
            }

            if($request->has('remotables')){
                $arr['remotable'] = $request->get('remotables');
            }

            if($request->has('can_parttime')) {
                $arr['can_parttime'] = 1;
            }

            $languages = $request->get('languages');

            $file_id = $request->get('file_id');
            $file_link = $request->get('file_link');
            $link = $file_link ?? ''; // set null = string ''
            if(is_null($file_id)) {
                $file_id = File::query()->create([
                    'post_id' => $arr['id'],
                    'link' => $link,
                    'type' => 1
                ])->id;
            }

            File::query()
                ->where('id', $file_id)
                ->update([
                    'link' => $link
                ]);

            $post = $this->model->create($arr);

            foreach ($languages as $language) {
                $language = Language::query()->firstOrCreate(['name' => $language]);
                ObjectLanguage::query()
                    ->updateOrCreate([
                        'object_id' => $post->id,
                        'language_id' => $language->id,
                        'object_type' => Post::class,
                    ]);
            }

            DB::commit();

            $message = "Edit success";

            return $this->successResponse([], $message);

        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function importCsv(Request $request)
    {
        try {
            $file = $request->file('file');
            $levels = $request->input('levels');

            Excel::import(new PostImport($levels), $file);
            return $this->successResponse();

        } catch (Throwable $e) {
            return $this->errorResponse();
        }
    }

    public function destroy($postId) {
        try {
            Post::destroy($postId);

            $message = "Delete success";

            return $this->successResponse([], $message);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

    }
}

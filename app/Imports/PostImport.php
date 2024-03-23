<?php

namespace App\Imports;

use App\Enums\FileTypeEnum;
use App\Enums\PostLevelEnum;
use App\Enums\PostRemotableEnum;
use App\Enums\PostStatusEnum;
use App\Models\Company;
use App\Models\File;
use App\Models\Language;
use App\Models\ObjectLanguage;
use App\Models\Post;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

class PostImport implements ToArray, WithHeadingRow
{
    public string $levels;

    public function __construct($levels)
    {
        $this->levels = $levels;
    }

    public function array(array $array): void
    {
        foreach ($array as $each) {
            try {
                $companyName = $each['cong_ty'];
                $language = $each['ngon_ngu'];
                $city = $each['dia_diem'];
                $link = $each['link'];
                if (!is_null($companyName) || !is_null($language) || !is_null($city) || !is_null($link)) {
                    $remotable = PostRemotableEnum::OFFICE_ONLY;

                    if ($each['dia_diem'] === 'Nhiều') {
                        $city = null;
                    } else {
                        if ($each['dia_diem'] === 'Remote') {
                            $remotable = PostRemotableEnum::REMOTE_ONLY;
                            $city = null;
                        } else {
                            $city = str_replace([
                                'HN',
                                'HCM',
                            ], [
                                'Hà Nội',
                                'Hồ Chí Minh',
                            ], $city);
                        }
                    }

                    if (!empty($companyName)) {
                        $companyId = Company::query()
                            ->firstOrCreate([
                                'name' => $companyName,
                            ], [
                                'city' => $city,
                                'country' => 'Vietnam',
                            ])->id;
                    } else {
                        $companyId = null;
                    }
                    $job_title = $this->generateJobTitle($city, $language, $companyName);

                    $post = Post::query()
                        ->firstOrCreate([
                            'job_title' => $job_title,
                            'levels' => $this->levels,
                            'company_id' => $companyId,
                            'city' => $city,
                            'remotable' => $remotable,
                            'status' => PostStatusEnum::ADMIN_APPROVED,
                        ]);

                    $languages = explode(',', $language);
                    foreach ($languages as $language) {
                        $objLanguage = Language::query()
                            ->firstOrCreate([
                                'name' => trim($language),
                            ]);
                        ObjectLanguage::query()
                            ->create([
                                'object_id' => $post->id,
                                'language_id' => $objLanguage->id,
                                'object_type' => Post::class,
                            ]);
                    }

                    File::query()
                        ->create([
                            'post_id' => $post->id,
                            'link' => $link,
                            'type' => FileTypeEnum::JD,
                        ]);
                }

            } catch (Throwable $e) {
                dd($each, $e);
            }
        }
    }

//    ($level - $city) $languages - $companyName
    private function generateJobTitle($city, $language, $companyName): string
    {
        $levelVals = explode(',', $this->levels);
        $levelKeys = array_map(function ($val) {
            return PostLevelEnum::getKey($val);
        }, $levelVals);

        $levels = implode(', ', $levelKeys);
        $title = '(';
        $title .= $levels;

        if($city){
            $title .= ' - ' . $city ;
        }

        $title .= ') ';

        $title .= $language;

        if(!empty($companyName)){
            $title .= ' - ' . $companyName;
        }
        return trim($title);
    }
}

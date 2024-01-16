<?php

namespace App\Imports;

use App\Enums\FileTypeEnum;
use App\Enums\PostRemotableEnum;
use App\Enums\PostStatusEnum;
use App\Models\Company;
use App\Models\File;
use App\Models\Language;
use App\Models\Post;
use http\Client\Response;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PostImport implements ToArray, WithHeadingRow
{

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

                    $languages = explode(',', $language);
                    foreach ($languages as $language) {
                        Language::query()
                            ->firstOrCreate([
                                'name' => trim($language),
                            ]);
                    }

                    $post = Post::query()
                        ->firstOrCreate([
                            'job_title' => $language,
                            'company_id' => $companyId,
                            'city' => $city,
                            'remotable' => $remotable,
                            'status' => PostStatusEnum::ADMIN_APPROVED,
                        ]);

                    File::query()
                        ->create([
                            'post_id' => $post->id,
                            'link' => $link,
                            'type' => FileTypeEnum::JD,
                        ]);
                }

            } catch (\Throwable $e) {
                dd($each, $e);
            }
        }
    }
}

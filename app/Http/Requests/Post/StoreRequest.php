<?php

namespace App\Http\Requests\Post;

use App\Enums\PostCurrencySalaryEnum;
use App\Enums\PostRemotableEnum;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         $rules = [
            'company' => [
                'string',
                'nullable',
            ],
            'languages' => [
                'required',
                'filled',
                'array',
            ],
            'city' => [
                'required',
                'filled',
                'string',
            ],
            'district' => [
                'string',
                'nullable',
            ],
            'currency_salary' => [
                'required',
                'numeric',
                Rule::in(PostCurrencySalaryEnum::getValues())
            ],
            'number_applicant' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'remotable' => [
                'nullable',
                Rule::in(PostRemotableEnum::getArrWithoutAll()),
            ],
            'is_parttime' => [
                'nullable',
            ],
            'start_date' => [
                'nullable',
                'date',
                'before:end_date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after:start_date',
            ],
            'job_title' => [
                'required',
                'string',
                'filled',
                'min:3',
                'max:255',
            ],
            'slug' => [
                'required',
                'string',
                'filled',
                'min:3',
                'max:255',
                Rule::unique(Post::class)
            ],
        ];

        $rules['min_salary'] = [
            'nullable',
            'numeric',
        ];

        if (!empty($this->max_salary)) {
            $rules['min_salary'][] = 'lt:max_salary';
        }

        $rules['max_salary'] = [
            'nullable',
            'numeric',
        ];

        if (!empty($this->min_salary)) {
            $rules['max_salary'][] = 'gt:min_salary';
        }

         return $rules;
    }
}

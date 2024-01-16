<?php

namespace App\Http\Requests\Company;

use App\Enums\CompanyCountryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'filled',
                'min:0',
            ],
            'country' => [
                'required',
                'string',
                Rule::in(CompanyCountryEnum::getKeys()),
            ],
            'city' => [
                'required',
                'string',
            ],
            'district' => [
                'nullable',
                'string',
            ],
            'address' => [
                'nullable',
                'string',
            ],
            'zipcode' => [
                'nullable',
                'string',
            ],
            'email' => [
                'nullable',
                'string',
            ],
            'logo' => [
                'nullable',
                'file',
                'image',
                'max:50000',
            ],
        ];
    }
}

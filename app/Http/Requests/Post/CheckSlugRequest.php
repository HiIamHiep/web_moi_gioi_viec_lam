<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckSlugRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'slug' => [
                'string',
                'required',
                'filled',
                Rule::unique(Post::class)->ignore($this->post),
            ],
        ];
    }
}

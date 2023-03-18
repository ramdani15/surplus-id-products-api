<?php

namespace App\Http\Requests\Api\V1\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'enable' => ['required', 'integer', 'in:1,0'], // Note: Swagger can't send true/false in boolean on form-data
            'image' => ['nullable', 'image'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }
}

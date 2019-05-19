<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LevelStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'required|integer',
            'to' => 'required|integer',
            'is_basic' => [
                'required',
                Rule::in([1, 0]),
            ]
        ];
    }

    public function messages()
    {
        return [
            'from.required' => 'from is required!',
            'to.required' => 'to is required!',
            'is_basic.required' => 'is_basic is required!'
        ];
    }
}

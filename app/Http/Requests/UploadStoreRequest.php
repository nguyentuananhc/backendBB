<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadStoreRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'destination' => [
                'required',
                Rule::in(['avatar', 'question']),
            ],
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'image is required!',
            'image.max' => 'image is bigger than 2048!',
            'image.mimes' => 'image type wrong!',
        ];
    }
}

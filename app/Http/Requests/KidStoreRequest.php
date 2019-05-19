<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KidStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birth_day' => 'required|string',
//            'last_time_test' => 'required|date|date_format:Y-m-d H:i',
            'user_id' => 'required|integer',
            'gender' => [
                'required',
                Rule::in([1, 0]),
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'name is required!',
            'avatar_link.required' => 'avatar_link is required!',
            'birth_day.required' => 'birth_day is required!',
            'last_time_test.required' => 'last_time_test is required!',
            'user_id.required' => 'user_id is required!'
        ];
    }
}

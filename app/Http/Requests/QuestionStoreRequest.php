<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionStoreRequest extends FormRequest
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
            'position' => 'required|integer',
            'content' => 'required',
            'part_id' => 'required|integer',
            'level_id' => 'required|integer',
            'list_answers' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'position.required' => 'position is required!',
            'content.required' => 'content is required!',
            'part_id.required' => 'part_id is required!',
            'level_id.required' => 'level_id is required!',
        ];
    }
}

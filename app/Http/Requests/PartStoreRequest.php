<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartStoreRequest extends FormRequest
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
            'content' => 'required|string',
            'max_score' => 'required|numeric',
            'image' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'total_scores.required' => 'total_scores is required!',
            'user_id.required' => 'user_id is required!',
            'level_id.required' => 'level_id is required!',
            'part_id.required' => 'part_id is required!',
            'test_evaluate_id.required' => 'test_evaluate_id is required!'
        ];
    }
}

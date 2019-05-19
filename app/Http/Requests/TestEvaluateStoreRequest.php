<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestEvaluateStoreRequest extends FormRequest
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
            'content' => 'required',
            'level_id' => 'required|integer',
            'part_id' => 'required|integer',
            'min_score' => 'required|integer',
            'max_score' => 'required|integer|greater_than_field:min_score',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'content is required!',
            'level_id.required' => 'level_id is required!',
            'part_id.required' => 'part_id is required!',
            'min_score.required' => 'min_score is required!',
            'max_score.required' => 'max_score is required!',
            'max_score.greater_than_field' => 'max_score must greater than min_score',
        ];
    }
}

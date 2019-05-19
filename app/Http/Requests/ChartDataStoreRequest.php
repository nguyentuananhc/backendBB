<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChartDataStoreRequest extends FormRequest
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
            'level_id' => 'required|integer',
            'part_id' => 'required|integer',
            'max_value' => 'required|integer|greater_than_field:min_score',
        ];
    }

    public function messages()
    {
        return [
            'level_id.required' => 'level_id is required!',
            'part_id.required' => 'part_id is required!',
            'max_value.required' => 'max_score is required!',
        ];
    }
}

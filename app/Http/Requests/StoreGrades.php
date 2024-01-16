<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrades extends FormRequest
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
            'name_ar' => 'required|unique:grades,name_ar,'.$this->id,
            'name_en' => 'required|unique:grades,name_en,'.$this->id,
            'notes' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name_ar.required' => 'this field is required',
            'name_ar.unique' => 'this field unique',
            'name_en.required' => 'this field is required',
            'name_en.unique' => 'this field unique',
        ];
    }
}

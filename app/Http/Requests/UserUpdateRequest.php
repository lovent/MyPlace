<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'required',
            'avatar' => 'image|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.user-name-required'),
            'max' => trans('validation.image-max'),
            'image' => trans('validation.image-wrong'),
        ];
    }
}

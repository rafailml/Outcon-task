<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserProfileRequest extends FormRequest
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
            'name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'email|max:255|unique:users,email,'. Auth::user()->id,
            'password' => 'string|confirmed|max:255|nullable',
            'password_confirmation' => 'string|max:255|nullable',
        ];
    }
}

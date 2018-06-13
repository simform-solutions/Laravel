<?php

namespace App\Http\Requests;

use App\Rules\ValidateImage;
use App\Rules\ValidateOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'string|max:30',
            'last_name' => 'string|max:30',
            'email' => 'string|email|max:100|unique:users,email,' . \auth()->user()->getAuthIdentifier(),
            'password' => 'required_with:current_password|string|min:6|max:20|different:current_password',
            'current_password' => [
                'required_with:password',
                new ValidateOldPassword
            ],
            'avatar' => [
                new ValidateImage,
                'unique:users,avatar,' . \auth()->user()->getAuthIdentifier()
            ],
            'mobile_number' => 'required|string|max:20|phone|unique:users,mobile_number,' . \auth()->user()->getAuthIdentifier(),
            'mobile_number_country' => 'required_with:mobile_number'
        ];
    }
}

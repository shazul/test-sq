<?php

namespace Pimeo\Http\Requests\Pim\User;

use Pimeo\Http\Requests\Request;

class ProfileUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->route('user');

        return auth()->user()->id == $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'            => 'required|max:255',
            'last_name'             => 'required|max:255',
            'email'                 => 'required|email|max:255|unique:users,email,' . $this->route('user')->id,
            'current_password'      => 'required|check_password',
            'password'              => 'sometimes|min:6|confirmed',
            'password_confirmation' => '',
        ];
    }
}

<?php

namespace Pimeo\Http\Requests\Pim\User;

use Pimeo\Http\Requests\Request;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('edit', $this->route('user'));
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
            'password'              => 'sometimes|min:6|confirmed',
            'password_confirmation' => '',
            'groups'                => 'required|allowed_groups',
            'active'                => '',
            'companies'             => 'required'
        ];
    }
}

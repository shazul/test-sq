<?php

namespace Pimeo\Http\Requests\Pim\User;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\User;

class CreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
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
            'email'                 => 'required|email|max:255|unique:users',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'groups'                => 'required|allowed_groups',
            'active'                => '',
            'companies'             => 'required'
        ];
    }
}

<?php

namespace App\Http\Requests;

// use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'last_name' => 'required|string|min:1|max:256',
            'first_name' => 'required|string|min:1|max:256',
            'state' => 'required|boolean',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'groups_id' => 'nullable|array|exists:groups,id',
        ];
    }


}
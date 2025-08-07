<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpSertUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

        if ($this->isMethod('PUT')) {
            $rules['uuid'] = 'required|exists:tb_users,uuid';
        }

        return array_merge($rules, [
            'name' => [
                'required',
                'min:4',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('tb_users')->ignore($this->route('uuid'), 'uuid')
            ],
            'password' => [
                'sometimes',
                'min:8',
                'max:255',
            ]
        ]);
    }
}

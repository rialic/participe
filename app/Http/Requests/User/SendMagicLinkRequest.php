<?php

namespace App\Http\Requests\User;

use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;

class SendMagicLinkRequest extends FormRequest
{
    use HasRequestResource;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'email.exists' => 'Email não está cadastrado no sistema.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:150', "exists:tb_users"]
        ];
    }
}

<?php

namespace App\Http\Requests\Certificate;

use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;

class ShowCertificateRequest extends FormRequest
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
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.exists' => 'CPF não existe no sistema.'
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
            'cpf' => ['required', 'cpf', "exists:tb_users,cpf"],
        ];
    }
}

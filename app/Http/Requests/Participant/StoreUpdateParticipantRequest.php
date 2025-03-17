<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateParticipantRequest extends FormRequest
{
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
            'cpf.unique' => 'CPF já está sendo utilizado no sistema.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   $isPostMethod = $this->method() === 'POST';

        if ($isPostMethod) {
            return [
                'cpf' => ['required', 'cpf', "unique:tb_users,cpf"],
                'name' => ['required', 'string',"regex:/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð,.'-]+\040[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð\040,.'-]+$/", 'max:150'],
                'sex' => ['required'],
                'email' => ['required', 'email', 'max:150', "unique:tb_users,email"],
                'cbo' => ['required', 'string', 'exists:tb_cbos,uuid'],
                'city' => ['required', 'string', 'exists:tb_cities,uuid'],
                'establishment' => ['required', 'string', 'exists:tb_establishments,uuid'],
            ];
        }

        return [
            'uuid' => ['required', 'exists:tb_users,uuid'],
            'cpf' => ['required', 'cpf', "unique:tb_users,cpf,{$this->uuid},uuid"],
            'name' => ['required', 'string',"regex:/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð,.'-]+\040[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð\040,.'-]+$/", 'max:150'],
            'sex' => ['required'],
            'email' => ['required', 'email', 'max:150', "unique:tb_users,email,{$this->uuid},uuid"],
            'cbo' => ['required', 'string', 'exists:tb_cbos,uuid'],
            'city' => ['required', 'string', 'exists:tb_cities,uuid'],
            'establishment' => ['required', 'string', 'exists:tb_establishments,uuid'],
        ];

    }
}

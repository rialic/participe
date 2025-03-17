<?php

namespace App\Http\Requests\City;

use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;

class IndexCityRequest extends FormRequest
{
    use HasRequestResource;

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
        return [
            'name' => ['sometimes', 'string'],
            'state' => ['sometimes', 'string', 'exists:tb_states,uuid']
        ];
    }
}

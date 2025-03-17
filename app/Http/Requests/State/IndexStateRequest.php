<?php

namespace App\Http\Requests\State;

use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;

class IndexStateRequest extends FormRequest
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
            'acronym' => ['sometimes', 'string', 'max:3']
        ];
    }
}

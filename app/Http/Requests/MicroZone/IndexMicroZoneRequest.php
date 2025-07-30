<?php

namespace App\Http\Requests\MicroZone;

use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;

class IndexMicroZoneRequest extends FormRequest
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
            'macro_zone' => ['sometimes', 'string', 'exists:tb_macro_zones,uuid']
        ];
    }
}

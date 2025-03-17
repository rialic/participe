<?php

namespace App\Http\Requests\Certificate;

use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;

class PrintCertificateRequest extends FormRequest
{
    use HasRequestResource;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_uuid' => $this->route('user'),
            'event_uuid' => $this->route('event'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_uuid' => ['required', 'exists:tb_users,uuid'],
            'event_uuid' => ['required', 'exists:tb_events,uuid']
        ];
    }
}

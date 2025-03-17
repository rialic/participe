<?php

namespace App\Http\Requests\Event;

use App\Rules\ParticipantsInUserRule;
use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;

class SyncParticipantsRequest extends FormRequest
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
            'uuid' => ['required', 'exists:tb_events,uuid'],
            'participants' => ['required', new ParticipantsInUserRule]
        ];
    }
}

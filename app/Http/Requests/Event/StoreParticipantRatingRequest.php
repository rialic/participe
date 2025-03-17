<?php

namespace App\Http\Requests\Event;

use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;

class StoreParticipantRatingRequest extends FormRequest
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
            'event_uuid' => $this->route('uuid'),
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
            'event_uuid' => ['required', 'exists:tb_events,uuid'],
            'participant' => ['required', 'exists:tb_users,uuid'],
            'rating_event' => ['integer'],
            'rating_event_schedule' => ['integer'],
            'hint' => ['nullable', 'string', 'max:500'],
        ];
    }
}

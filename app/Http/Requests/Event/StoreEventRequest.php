<?php

namespace App\Http\Requests\Event;

use App\Enums\TypeEvent;
use App\Traits\HasRequestResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
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
            'name.required' => 'O campo Tema é obrigatório.',
            'name.max' => 'O campo Tema não pode ultrapassar 255 caracteres.',
            'cities_to_notify.required' => 'O campo Cidades é obrigatório.',
            'select_group_emails.required' => 'O campo Emails a serem notificados de uma nova agenda de webaula é obrigatório.'
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
            'name' => 'required|max:255',
            'organization' => 'required|in:TSMS,Fiocruz',
            'room_link' => 'required|url:http,https',
            'descs' => 'required|exists:tb_descs,uuid',
            'summary_emails' => 'nullable|json',
            'start_at' => 'required',
            'start_minutes_additions' => 'required|integer',
            'end_at' => 'required',
            'end_minutes_additions' => 'required|integer',
            'type_notification' => 'required|in:all,cities,group,none',
            'cities_to_notify' => ['nullable', 'json', Rule::requiredIf($this->type_notification === 'cities')],
            'select_group_emails' => ['nullable', 'json', Rule::requiredIf($this->type_notification === 'group')],
            'type_event' => ['required', Rule::enum(TypeEvent::class)]
        ];
    }
}

<?php

namespace App\Rules;

use App\Repository\UserRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ParticipantsInUserRule implements ValidationRule
{
    private $userRepository;

    public function __construct(){
        $this->userRepository = new UserRepository;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $participants = $value;
        $userList = $this->userRepository->getModel()->whereIn('uuid', $participants)->get();

        if($userList->isEmpty() || ($userList->count() !== count($participants))) {
            $fail('Lista de participantes ausente ou não foram cadastrados no sistema.');
        }
    }
}

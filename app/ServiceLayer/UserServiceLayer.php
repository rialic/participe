<?php

namespace App\ServiceLayer;

use App\Mail\MagicLoginLink;
use App\Repository\Interfaces\UserInterface as UserRepository;
use App\ServiceLayer\Base\ServiceResource;
use Illuminate\Support\Facades\Mail;

class UserServiceLayer extends ServiceResource {

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function me($data): ?object
    {
        return $data;
    }

    public function sendMagicLink($data)
    {
        $user = $this->repository->getFirstData(['email' => $data['email']]);

        Mail::to($data['email'])->queue(new MagicLoginLink($user->name, $data['email']));
    }
}
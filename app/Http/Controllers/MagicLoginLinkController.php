<?php

namespace App\Http\Controllers;

use App\Models\User;

class MagicLoginLinkController extends Controller
{
    public function __invoke(User $user)
    {
        auth()->login($user);

        return redirect(config('app.url') . '/home');
    }
}

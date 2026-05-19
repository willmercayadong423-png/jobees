<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    public function handle($role)
    {
        $isLoggedIn = Session::has('user');

        // guest only pages (login/register)
        if ($role === 'guest' && $isLoggedIn) {
            header('Location: /');
            exit;
        }

        // auth only pages (protected)
        if ($role === 'auth' && !$isLoggedIn) {
            header('Location: /auth/login');
            exit;
        }

        return true;
    }
}
<?php

namespace Framework;

class Authorization
{
    public static function isOwner($userId)
    {
        $sessionUser = Session::get('user');

        // Check if logged in user exists
        if ($sessionUser !== null && isset($sessionUser['id'])) {

            $sessionUserId = (int) $sessionUser['id'];

            return $sessionUserId === (int) $userId;
        }

        return false;
    }
}
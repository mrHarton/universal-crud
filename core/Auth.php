<?php

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user']);
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function isAdmin()
    {
        return self::user()['role'] === 'admin';
    }
}

<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login;

class LoginScreenPage extends Login
{
    public function __construct() {
        static::$view = 'vendor.filament-login-screen.themes.theme3.index';
        static::$layout = 'vendor.filament-login-screen.themes.base';
    }
}
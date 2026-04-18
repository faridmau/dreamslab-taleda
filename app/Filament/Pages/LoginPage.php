<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login;
use Illuminate\Contracts\Support\Htmlable;

class LoginPage extends Login
{
    public function getTitle(): string|Htmlable
    {
        return 'Taleda Login';
    }

    public function getHeading(): string|Htmlable|null
    {
        return '';
    }
}

<?php

namespace App\Traits;

use Filament\Support\Enums\Width;

trait MaxContentWidth
{
    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }
}

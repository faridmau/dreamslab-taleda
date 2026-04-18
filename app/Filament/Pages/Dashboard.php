<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\OverviewWidget;
use App\Traits\MaxContentWidth;
use Filament\Widgets\Widget;

class Dashboard extends \Filament\Pages\Dashboard
{
    use MaxContentWidth;

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            OverviewWidget::class,
        ];
    }
}

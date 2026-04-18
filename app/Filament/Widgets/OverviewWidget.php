<?php

namespace App\Filament\Widgets;

use App\Models\MagentoCategory;
use App\Models\MagentoCustomer;
use App\Models\MagentoOrder;
use App\Models\MagentoProduct;
use Filament\Widgets\Widget;
use Illuminate\View\View;

class OverviewWidget extends Widget
{
    protected int|string|array $columnSpan = 'full';

    public function render(): View
    {
        $stats = [
            'total_categories' => MagentoCategory::count(),
            'total_customers' => MagentoCustomer::count(),
            'total_orders' => MagentoOrder::count(),
            'total_products' => MagentoProduct::count(),
        ];

        return view('filament.widgets.overview-widget', compact('stats'));
    }
}

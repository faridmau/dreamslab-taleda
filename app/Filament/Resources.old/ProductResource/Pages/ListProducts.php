<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Traits\MaxContentWidth;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    use MaxContentWidth;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - Magento data is read-only
        ];
    }
}

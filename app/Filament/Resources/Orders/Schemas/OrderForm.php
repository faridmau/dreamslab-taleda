<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Components;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make('Read-Only Information')
                    ->description('Orders from Magento cannot be modified through this interface.')
                    ->schema([
                        Components\TextInput::make('entity_id')
                            ->label('Order ID')
                            ->disabled(),
                        Components\TextInput::make('increment_id')
                            ->label('Order Number')
                            ->disabled(),
                        Components\TextInput::make('status')
                            ->label('Status')
                            ->disabled(),
                        Components\TextInput::make('customer.email')
                            ->label('Customer Email')
                            ->disabled(),
                    ]),
            ]);
    }
}

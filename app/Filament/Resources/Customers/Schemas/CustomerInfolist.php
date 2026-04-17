<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Components\Section::make('Customer Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('entity_id')
                            ->label('Customer ID'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('firstname')
                            ->label('First Name'),
                        TextEntry::make('lastname')
                            ->label('Last Name'),
                        TextEntry::make('group_id')
                            ->label('Customer Group'),
                        TextEntry::make('store_id')
                            ->label('Store ID'),
                        TextEntry::make('website_id')
                            ->label('Website ID'),
                        TextEntry::make('is_active')
                            ->label('Status')
                            ->color(fn ($state) => $state ? 'success' : 'danger')
                            ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('d.m.Y H:i'),
                    ]),

                Components\Section::make('Addresses')
                    ->schema([
                        RepeatableEntry::make('addresses')
                            ->schema([
                                TextEntry::make('entity_id')
                                    ->label('Address ID'),
                                TextEntry::make('street')
                                    ->label('Street'),
                                TextEntry::make('city')
                                    ->label('City'),
                                TextEntry::make('region')
                                    ->label('Region'),
                                TextEntry::make('postcode')
                                    ->label('Postal Code'),
                                TextEntry::make('country_id')
                                    ->label('Country'),
                            ]),
                    ]),

                Components\Section::make('Recent Orders')
                    ->schema([
                        RepeatableEntry::make('orders')

                            ->schema([
                                TextEntry::make('entity_id')
                                    ->label('Order ID'),
                                TextEntry::make('increment_id')
                                    ->label('Order Number'),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge(),
                                TextEntry::make('grand_total')
                                    ->label('Total')
                                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
                                TextEntry::make('created_at')
                                    ->label('Date')
                                    ->dateTime('M d, Y'),
                            ]),
                    ]),
            ]);
    }
}

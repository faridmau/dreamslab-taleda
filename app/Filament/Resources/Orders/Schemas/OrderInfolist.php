<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make('Order Information')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('entity_id')->label('Order ID'),
                        TextEntry::make('increment_id')->label('Order Number'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'pending' => 'warning',
                                'processing' => 'info',
                                'complete' => 'success',
                                'closed' => 'gray',
                                'canceled' => 'danger',
                                'holded' => 'warning',
                                default => 'gray',
                            }),
                        TextEntry::make('customer.firstname')
                            ->label('Customer Name')
                            ->formatStateUsing(fn ($state, $record) => ($record->customer?->firstname ?? '').' '.($record->customer?->lastname ?? '')),
                        TextEntry::make('customer.email')->label('Customer Email'),
                        TextEntry::make('order_currency_code')->label('Currency'),
                    ]),

                Components\Section::make('Order Total')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('subtotal')->label('Subtotal')->money('USD'),
                        TextEntry::make('tax_amount')->label('Tax')->money('USD'),
                        TextEntry::make('shipping_amount')->label('Shipping')->money('USD'),
                        TextEntry::make('discount_amount')->label('Discount')->money('USD'),
                        TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->money('USD')
                            ->weight('bold'),
                    ]),

                Components\Section::make('Billing Address')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('billingAddress.firstname')
                            ->label('First Name')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('billingAddress.lastname')
                            ->label('Last Name')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('billingAddress.street')
                            ->label('Street')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('billingAddress.city')
                            ->label('City')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('billingAddress.region')
                            ->label('State/Region')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('billingAddress.postcode')
                            ->label('Postal Code')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('billingAddress.country_id')
                            ->label('Country')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('billingAddress.telephone')
                            ->label('Telephone')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                    ]),

                Components\Section::make('Shipping Address')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('shippingAddress.firstname')
                            ->label('First Name')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('shippingAddress.lastname')
                            ->label('Last Name')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('shippingAddress.street')
                            ->label('Street')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('shippingAddress.city')
                            ->label('City')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('shippingAddress.region')
                            ->label('State/Region')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('shippingAddress.postcode')
                            ->label('Postal Code')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('shippingAddress.country_id')
                            ->label('Country')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                        TextEntry::make('shippingAddress.telephone')
                            ->label('Telephone')
                            ->formatStateUsing(fn ($state) => $state ?? '—'),
                    ]),

                Components\Section::make('Order Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('item_id')->label('Item ID'),
                                TextEntry::make('sku')->label('SKU')->copyable(),
                                TextEntry::make('name')->label('Product Name'),
                                TextEntry::make('qty_ordered')->label('Qty'),
                                TextEntry::make('price')->label('Price')->money('USD'),
                                TextEntry::make('row_total')->label('Total')->money('USD'),
                            ])
                            ->columns(3),
                    ]),

                Components\Section::make('Dates')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')->label('Order Date')->dateTime(),
                        TextEntry::make('updated_at')->label('Last Updated')->dateTime(),
                    ]),
            ]);
    }
}

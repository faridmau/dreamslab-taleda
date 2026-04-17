<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entity_id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('increment_id')
                    ->label('Order Number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.email')
                    ->label('Customer Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.firstname')
                    ->label('Customer Name')
                    ->formatStateUsing(fn ($state, $record) => ($record->customer?->firstname ?? '').' '.($record->customer?->lastname ?? ''))
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('customer', function ($q) use ($search) {
                            $q->where('firstname', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),

                TextColumn::make('billingAddress.city')
                    ->label('Billing Address')
                    ->formatStateUsing(fn ($state, $record) => ($record->billingAddress?->street ?? '').' - '.($record->billingAddress?->city ?? '').' '.($record->billingAddress?->postcode ?? ''))
                    ->wrap()
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('billingAddress', function ($q) use ($search) {
                            $q->where('street', 'like', "%{$search}%")
                                ->orWhere('city', 'like', "%{$search}%")
                                ->orWhere('region', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('status')
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
                    })
                    ->sortable(),

                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('CHF')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'complete' => 'Complete',
                        'closed' => 'Closed',
                        'canceled' => 'Canceled',
                        'holded' => 'On Hold',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
            ])
            ->defaultSort('entity_id', 'desc')
            ->paginated([25, 50, 100])
            ->striped();
    }
}

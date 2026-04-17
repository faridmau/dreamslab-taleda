<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entity_id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->sortable()
                    ->searchable()
                    ->copyable(),

                // EAV columns — loaded via getNameAttribute() accessor
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable(query: function (Builder $query, string $search) {
                        // EAV-aware search: join varchar table
                        $query->whereIn('entity_id', function ($sub) use ($search) {
                            $sub->select('entity_id')
                                ->from('catalog_product_entity_varchar')
                                ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'catalog_product_entity_varchar.attribute_id')
                                ->where('eav_attribute.attribute_code', 'name')
                                ->where('catalog_product_entity_varchar.value', 'like', "%{$search}%")
                                ->where('catalog_product_entity_varchar.store_id', 0);
                        });
                    }),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('USD')
                    ->sortable(query: function (Builder $query, string $direction) {
                        $query->orderBy(function ($sub) {
                            $sub->select('value')
                                ->from('catalog_product_entity_decimal')
                                ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'catalog_product_entity_decimal.attribute_id')
                                ->where('eav_attribute.attribute_code', 'price')
                                ->whereColumn('catalog_product_entity_decimal.entity_id', 'catalog_product_entity.entity_id')
                                ->where('catalog_product_entity_decimal.store_id', 0)
                                ->limit(1);
                        }, $direction);
                    }),

                Tables\Columns\TextColumn::make('type_id')
                    ->label('Type')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'simple' => 'success',
                        'configurable' => 'info',
                        'bundle' => 'warning',
                        'virtual' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => $state === 'Enabled' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('stockItem.qty')
                    ->label('Stock Qty')
                    ->numeric(decimalPlaces: 0)
                    ->default('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type_id')
                    ->label('Product Type')
                    ->options([
                        'simple' => 'Simple',
                        'configurable' => 'Configurable',
                        'bundle' => 'Bundle',
                        'virtual' => 'Virtual',
                        'downloadable' => 'Downloadable',
                        'grouped' => 'Grouped',
                    ]),

                Tables\Filters\Filter::make('enabled')
                    ->label('Enabled only')
                    ->query(fn(Builder $q) => $q->enabled()),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}

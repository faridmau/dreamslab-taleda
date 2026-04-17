<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('entity_id')->label('Product ID'),
                        TextEntry::make('sku')->label('SKU')->copyable(),
                        TextEntry::make('name')->label('Name'),
                        TextEntry::make('type_id')->label('Type')->badge(),
                        TextEntry::make('status_label')->label('Status')
                            ->color(fn ($state) => $state === 'Enabled' ? 'success' : 'danger'),
                        TextEntry::make('visibility_label')->label('Visibility'),
                        TextEntry::make('url_key')->label('URL Key'),
                        TextEntry::make('created_at')->label('Created')->dateTime(),
                    ]),

                Components\Section::make('Pricing')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('price')->label('Price')->money('USD'),
                        TextEntry::make('special_price')->label('Special Price')->money('USD'),
                        TextEntry::make('cost')->label('Cost')->money('USD'),
                        TextEntry::make('weight')->label('Weight')->suffix(' kg'),
                        TextEntry::make('tax_class_id')->label('Tax Class'),
                    ]),

                Components\Section::make('Inventory')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('stockItem.qty')->label('Quantity'),
                        TextEntry::make('stockItem.min_qty')->label('Min Qty'),
                        TextEntry::make('stockItem.is_in_stock')
                            ->label('In Stock')
                            ->formatStateUsing(fn ($state) => $state ? 'In Stock' : 'Out of Stock')
                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                        TextEntry::make('stockItem.manage_stock')
                            ->label('Manage Stock')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    ]),

                Components\Section::make('Description')
                    ->schema([
                        TextEntry::make('short_description')
                            ->label('Short Description')
                            ->html(),
                        TextEntry::make('description')
                            ->label('Full Description')
                            ->html(),
                    ]),

                Components\Section::make('SEO')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('meta_title')->label('Meta Title'),
                        TextEntry::make('meta_description')->label('Meta Description'),
                    ]),

                Components\Section::make('Categories')
                    ->schema([
                        RepeatableEntry::make('categories')
                            ->label('')
                            ->schema([
                                TextEntry::make('entity_id')->label('Category ID'),
                                TextEntry::make('path')->label('Path'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            // No edit or delete actions - Magento data is read-only
        ];
    }
}

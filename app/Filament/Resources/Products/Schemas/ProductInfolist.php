<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Components\Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('entity_id')->label('Product ID'),
                        TextEntry::make('sku')->label('SKU')->copyable(),
                        TextEntry::make('name')->label('Name'),
                        TextEntry::make('type_id')->label('Type')->badge(),
                        TextEntry::make('brand')->label('Brand'),
                        TextEntry::make('reference')->label('Reference'),
                        TextEntry::make('model')->label('Model'),
                        TextEntry::make('year')->label('Year'),
                        TextEntry::make('condition')->label('Condition'),
                        TextEntry::make('status_label')->label('Status')
                            ->color(fn ($state) => $state === 'Enabled' ? 'success' : 'danger'),
                        TextEntry::make('visibility_label')->label('Visibility'),
                        TextEntry::make('url_key')->label('URL Key'),
                        TextEntry::make('gender_label')->label('Gender'),
                        TextEntry::make('box')
                            ->label('Box')
                            ->formatStateUsing(fn ($state) => $state ? 'Si' : 'No'),
                        TextEntry::make('warranty')
                            ->label('Warranty')
                            ->formatStateUsing(fn ($state) => $state ? 'Si' : 'No'),
                        TextEntry::make('created_at')->label('Created')->dateTime('d.m.Y H:i'),
                    ]),

                Components\Section::make('Pricing')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('price')->label('Price')->money('CHF'),
                        TextEntry::make('special_price')->label('Special Price')->money('CHF'),
                        TextEntry::make('cost')->label('Cost')->money('CHF'),
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

                Components\Section::make('Cassa e Quadrante (Case and Dial)')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('diameter_case_label')->label('Case Diameter'),
                        TextEntry::make('waterproof_label')->label('Waterproof / Depth'),
                        TextEntry::make('case_material_label')->label('Case Material'),
                        TextEntry::make('material_bezel_label')->label('Bezel Material'),
                        TextEntry::make('dial_numerals_label')->label('Dial Numbers'),
                        TextEntry::make('dial_label')->label('Dial Color'),
                        TextEntry::make('glass_label')->label('Crystal Type'),
                    ]),

                Components\Section::make('Cinturino / Bracciale (Strap/Bracelet)')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('clasp_label')->label('Closure'),
                        TextEntry::make('strap_label')->label('Strap Material'),
                        TextEntry::make('bracelet_color_label')->label('Bracelet Color'),
                        TextEntry::make('buckle_material_label')->label('Closure Material'),
                    ]),

                Components\Section::make('Funzioni (Functions)')
                    ->schema([
                        TextEntry::make('movement_type_label')->label('Movement / Charge'),
                        TextEntry::make('is_new_label')->label('Condition'),
                    ]),

                Components\Section::make('Dettagli Gioiello (Jewelry Details)')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('gold_label')->label('Oro / Gold'),
                        TextEntry::make('jew_weight')->label('Peso / Weight'),
                        TextEntry::make('ringsize')->label('Misura Anello / Ring Size'),
                        TextEntry::make('jew_stones')->label('Pietre Preziose / Gemstones'),
                        TextEntry::make('peso_diamante')->label('Peso Diamante / Diamond Weight'),
                        TextEntry::make('colore_stone')->label('Colore Pietra / Stone Color'),
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
                                TextEntry::make('entity_id')->label('ID'),
                                TextEntry::make('name')->label('Category Name'),
                                TextEntry::make('level')->label('Level')->badge(),
                                TextEntry::make('status_label')->label('Status')
                                    ->badge()
                                    ->color(fn ($state) => $state === 'Active' ? 'success' : 'danger'),
                                TextEntry::make('path')->label('Path'),
                                TextEntry::make('pivot.position')->label('Position'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                Components\Section::make('Basic Information')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('entity_id')->label('Category ID'),
                        TextEntry::make('parent_id')->label('Parent Category ID'),
                        TextEntry::make('level')->label('Level'),
                    ]),

                Components\Section::make('Category Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')->label('Category Name'),
                        TextEntry::make('path')->label('Breadcrumb Path'),
                        TextEntry::make('status_label')->label('Status')
                            ->color(fn ($state) => $state === 'Active' ? 'success' : 'danger'),
                        TextEntry::make('is_anchor')
                            ->label('Is Anchor')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        TextEntry::make('position')->label('Position'),
                        TextEntry::make('children_count')->label('Sub-Categories'),
                        TextEntry::make('created_at')->label('Created')->dateTime(),
                        TextEntry::make('updated_at')->label('Updated')->dateTime(),
                    ]),

                Components\Section::make('Display Settings')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('display_mode')->label('Display Mode'),
                        TextEntry::make('landing_page')->label('Landing Page'),
                        TextEntry::make('image')->label('Image'),
                    ]),

                Components\Section::make('Description')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Category Description')
                            ->html(),
                    ]),

                Components\Section::make('Search Engine Optimization')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('meta_title')->label('Meta Title'),
                        TextEntry::make('meta_keywords')->label('Meta Keywords'),
                        TextEntry::make('meta_description')->label('Meta Description'),
                    ]),

                Components\Section::make('Category Hierarchy')
                    ->schema([
                        TextEntry::make('parent.name')
                            ->label('Parent Category')
                            ->default('—'),
                        TextEntry::make('children_count')
                            ->label('Child Categories Count'),
                    ]),

                Components\Section::make('Products')
                    ->schema([
                        RepeatableEntry::make('products')
                            ->schema([
                                TextEntry::make('entity_id')->label('Product ID'),
                                TextEntry::make('sku')->label('SKU')->copyable(),
                                TextEntry::make('name')->label('Product Name'),
                                TextEntry::make('pivot.position')->label('Position'),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }
}

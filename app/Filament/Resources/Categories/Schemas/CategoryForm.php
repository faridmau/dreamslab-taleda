<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make('Read-Only Information')
                    ->description('Category data is read-only from Magento')
                    ->columns(2)
                    ->schema([
                        TextInput::make('entity_id')
                            ->label('Category ID')
                            ->disabled(),
                        TextInput::make('name')
                            ->label('Category Name')
                            ->disabled(),
                        TextInput::make('parent_id')
                            ->label('Parent Category ID')
                            ->disabled(),
                        TextInput::make('level')
                            ->label('Level')
                            ->disabled(),
                        TextInput::make('position')
                            ->label('Position')
                            ->disabled(),
                        TextInput::make('children_count')
                            ->label('Sub-Categories')
                            ->disabled(),
                    ]),
            ]);
    }
}

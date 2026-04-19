<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entity_id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->where('catalog_category_entity.entity_id', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('name')
                    ->label('Category Name')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        // EAV-aware sorting: join varchar table
                        return $query
                            ->leftJoin('catalog_category_entity_varchar', function ($join) {
                                $join->on('catalog_category_entity.entity_id', '=', 'catalog_category_entity_varchar.entity_id')
                                    ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'catalog_category_entity_varchar.attribute_id')
                                    ->where('eav_attribute.attribute_code', 'name')
                                    ->where('catalog_category_entity_varchar.store_id', 0);
                            })
                            ->orderBy('catalog_category_entity_varchar.value', $direction)
                            ->distinct();
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        // EAV-aware search: join varchar table
                        $query->whereIn('catalog_category_entity.entity_id', function ($sub) use ($search) {
                            $sub->select('catalog_category_entity_varchar.entity_id')
                                ->from('catalog_category_entity_varchar')
                                ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'catalog_category_entity_varchar.attribute_id')
                                ->where('eav_attribute.attribute_code', 'name')
                                ->where('catalog_category_entity_varchar.value', 'like', "%{$search}%")
                                ->where('catalog_category_entity_varchar.store_id', 0);
                        });
                    })
                    ,

                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        0 => 'gray',
                        1 => 'info',
                        2 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'Active' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('position')
                    ->label('Position')
                    ->sortable(),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('Sub-Categories')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->default('—'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            // ->defaultSort('entity_id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label('Level')
                    ->options([
                        0 => 'Root',
                        1 => 'Level 1',
                        2 => 'Level 2',
                        3 => 'Level 3',
                        4 => 'Level 4',
                        5 => 'Level 5+',
                    ]),

                Tables\Filters\Filter::make('active')
                    ->label('Active only')
                    ->query(fn (Builder $q) => $q->active()),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                // ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\MagentoCustomer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = MagentoCustomer::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $modelLabel = 'Customer';

    protected static ?string $pluralModelLabel = 'Customers';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make('Customer Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('entity_id')
                            ->label('Customer ID')
                            ->disabled()
                            ->helperText('Read-only from Magento'),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->disabled(),
                        TextInput::make('firstname')
                            ->label('First Name')
                            ->disabled(),
                        TextInput::make('lastname')
                            ->label('Last Name')
                            ->disabled(),
                        TextInput::make('group_id')
                            ->label('Customer Group')
                            ->disabled(),
                        TextInput::make('store_id')
                            ->label('Store ID')
                            ->disabled(),
                        TextInput::make('website_id')
                            ->label('Website ID')
                            ->disabled(),
                        TextInput::make('is_active')
                            ->label('Status')
                            ->disabled(),
                    ]),
                Components\Section::make('Dates')
                    ->columns(2)
                    ->schema([
                        TextInput::make('created_at')
                            ->label('Created At')
                            ->disabled(),
                        TextInput::make('updated_at')
                            ->dateTime('d.m.Y H:i')
                            ->label('Updated At')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entity_id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Last Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_id')
                    ->label('Store')
                    ->sortable(),
                Tables\Columns\TextColumn::make('group_id')
                    ->label('Group')
                    ->sortable()
                    ->badge(),
                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Status')
                    ->colors([
                        'success' => 1,
                        'danger' => 0,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('store_id')
                    ->label('Store')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('group_id')
                    ->label('Customer Group')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // No bulk actions - Magento data is read-only
                ]),
            ])
            // ->paginate(25)
            ->persistSearchInSession()
            ->persistSortInSession()
            ->persistFiltersInSession();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderByDesc('entity_id');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }
}

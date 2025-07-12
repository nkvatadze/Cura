<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplexResource\Pages;
use App\Models\Complex;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplexResource extends Resource
{
    protected static ?string $model = Complex::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Property Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter complex name'),

                                TextInput::make('location')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('City, State'),
                            ]),

                        TextInput::make('block_quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Number of blocks'),
                    ])
                    ->collapsible(),

                Section::make('Status & Dates')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('status')
                                    ->label('Active')
                                    ->helperText('When inactive, complex is not visible to end users')
                                    ->default(false),
                            ]),

                        Grid::make(2)
                            ->schema([
                                DatePicker::make('construction_date')
                                    ->required()
                                    ->placeholder('Construction start date'),

                                DatePicker::make('completion_date')
                                    ->placeholder('Completion date'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('location')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('block_quantity')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                ToggleColumn::make('status')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('construction_date')
                    ->date()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('completion_date')
                    ->date()
                    ->sortable()
                    ->alignCenter()
                    ->placeholder('Not completed'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->label('Active Only'),

                Filter::make('completed')
                    ->label('Completed Complexes')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('completion_date')),

                Filter::make('under_construction')
                    ->label('Under Construction')
                    ->query(fn (Builder $query): Builder => $query->whereNull('completion_date')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\ComplexResource\RelationManagers\BlocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplexes::route('/'),
            'create' => Pages\CreateComplex::route('/create'),
            'edit' => Pages\EditComplex::route('/{record}/edit'),
            'view' => Pages\ViewComplex::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}

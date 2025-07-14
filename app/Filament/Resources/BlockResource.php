<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlockResource\Pages;
use App\Models\Block;
use App\Models\Complex;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

class BlockResource extends Resource
{
    protected static ?string $model = Block::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Property Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Block Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('complex_id')
                                    ->label('Complex')
                                    ->options(Complex::pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->placeholder('Select a complex'),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Block name'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('flat_quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->placeholder('Number of flats'),
                                TextInput::make('commercial_space_quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->placeholder('Number of commercial spaces'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Toggle::make('status')
                                    ->label('Active')
                                    ->helperText('When inactive, block is not visible to end users')
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
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('complex.name')
                    ->label('Complex')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('flat_quantity')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('commercial_space_quantity')
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
                    ->label('Completed Blocks')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('completion_date')),
                Filter::make('under_construction')
                    ->label('Under Construction')
                    ->query(fn (Builder $query): Builder => $query->whereNull('completion_date')),
                Filter::make('residential_blocks')
                    ->label('Residential Blocks (More Flats)')
                    ->query(fn (Builder $query): Builder => $query->where('flat_quantity', '>', 'commercial_space_quantity')),
                Filter::make('commercial_blocks')
                    ->label('Commercial Blocks (More Commercial Spaces)')
                    ->query(fn (Builder $query): Builder => $query->where('commercial_space_quantity', '>', 'flat_quantity')),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlocks::route('/'),
            'create' => Pages\CreateBlock::route('/create'),
            'edit' => Pages\EditBlock::route('/{record}/edit'),
            'view' => Pages\ViewBlock::route('/{record}'),
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

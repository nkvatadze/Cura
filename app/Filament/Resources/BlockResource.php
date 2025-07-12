<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuildingResource\Pages;
use App\Models\Block;
use App\Models\Complex;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('complex_id')
                                    ->label('Complex')
                                    ->options(Complex::pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->placeholder('Select a complex'),

                                TextInput::make('building_code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('BLD-1001')
                                    ->helperText('Unique building identifier'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Building name'),

                                TextInput::make('floors')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('Number of floors'),
                            ]),

                        Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Describe the building...'),
                    ])
                    ->collapsible(),

                Section::make('Units & Pricing')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('total_units')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('Total units'),

                                TextInput::make('available_units')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->placeholder('Available units'),

                                TextInput::make('price_per_unit')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->placeholder('Price per unit'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Classification & Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('type')
                                    ->required()
                                    ->options([
                                        'residential' => 'Residential',
                                        'commercial' => 'Commercial',
                                        'mixed_use' => 'Mixed Use',
                                        'luxury' => 'Luxury',
                                        'affordable' => 'Affordable',
                                    ])
                                    ->default('residential'),

                                Select::make('status')
                                    ->required()
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'maintenance' => 'Maintenance',
                                        'construction' => 'Under Construction',
                                        'sold_out' => 'Sold Out',
                                    ])
                                    ->default('active'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Construction Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('construction_year')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(now()->year + 5)
                                    ->placeholder('Construction year'),

                                DatePicker::make('completion_date')
                                    ->placeholder('Completion date'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('architect')
                                    ->placeholder('Architect firm'),

                                TextInput::make('contractor')
                                    ->placeholder('Contractor company'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('square_footage')
                                    ->numeric()
                                    ->suffix('sq ft')
                                    ->placeholder('Total square footage'),

                                TextInput::make('parking_spaces')
                                    ->placeholder('Number of parking spaces'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Features & Amenities')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('has_elevator')
                                    ->label('Has Elevator')
                                    ->default(false),

                                Toggle::make('has_security')
                                    ->label('Has Security System')
                                    ->default(false),
                            ]),

                        TagsInput::make('amenities')
                            ->placeholder('Add amenities...')
                            ->suggestions([
                                'Elevator',
                                'Security System',
                                'Parking',
                                'Gym',
                                'Pool',
                                'Garden',
                                'BBQ Area',
                                'Storage',
                                'Laundry',
                                'WiFi',
                                'Air Conditioning',
                                'Balcony',
                                'Pet Friendly',
                                'Bike Storage',
                                'Concierge',
                            ]),

                        TextInput::make('energy_rating')
                            ->placeholder('Energy rating (A-E)')
                            ->maxLength(1),
                    ])
                    ->collapsible(),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('images')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->maxFiles(5)
                            ->directory('buildings')
                            ->placeholder('Upload building images'),
                    ])
                    ->collapsible(),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->placeholder('Additional notes...'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('complex.name')
                    ->label('Complex')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('building_code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Building code copied!'),

                TextColumn::make('floors')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('total_units')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('available_units')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->color(fn (Block $record): string => $record->available_units === 0 ? 'danger' : 'success'),

                TextColumn::make('price_per_unit')
                    ->money('USD')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'blue' => 'residential',
                        'green' => 'commercial',
                        'purple' => 'mixed_use',
                        'amber' => 'luxury',
                        'emerald' => 'affordable',
                    ]),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'maintenance',
                        'info' => 'construction',
                        'gray' => 'sold_out',
                    ]),

                TextColumn::make('occupancy_rate')
                    ->label('Occupancy')
                    ->formatStateUsing(fn (Block $record): string => $record->occupancy_rate.'%')
                    ->alignCenter()
                    ->color(fn (Block $record): string => $record->occupancy_rate >= 90 ? 'success' : 'warning'),

                TextColumn::make('age')
                    ->label('Age')
                    ->formatStateUsing(fn (Block $record): string => $record->age.' years')
                    ->alignCenter(),

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
                SelectFilter::make('complex')
                    ->relationship('complex', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('type')
                    ->options([
                        'residential' => 'Residential',
                        'commercial' => 'Commercial',
                        'mixed_use' => 'Mixed Use',
                        'luxury' => 'Luxury',
                        'affordable' => 'Affordable',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                        'construction' => 'Under Construction',
                        'sold_out' => 'Sold Out',
                    ]),

                Filter::make('with_available_units')
                    ->label('With Available Units')
                    ->query(fn (Builder $query): Builder => $query->where('available_units', '>', 0)),

                Filter::make('under_construction')
                    ->label('Under Construction')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'construction')),

                Filter::make('high_occupancy')
                    ->label('High Occupancy (90%+)')
                    ->query(fn (Builder $query): Builder => $query->whereRaw('((total_units - available_units) / total_units * 100) >= 90')),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuildings::route('/'),
            'create' => Pages\CreateBuilding::route('/create'),
            'edit' => Pages\EditBuilding::route('/{record}/edit'),
            'view' => Pages\ViewBuilding::route('/{record}'),
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
        return static::getModel()::count() > 20 ? 'warning' : 'primary';
    }
}

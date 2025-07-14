<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(100)
                    ->searchable(),

                Tables\Columns\IconColumn::make('pinned_at')
                    ->label('Pinned')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-minus')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pinned_at')
                    ->label('Pinned At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('pinned_at')
                    ->label('Pinned Status')
                    ->placeholder('All Posts')
                    ->trueLabel('Pinned Posts')
                    ->falseLabel('Unpinned Posts'),
            ])
            ->actions([
                Tables\Actions\Action::make('pin')
                    ->label('Pin')
                    ->icon('heroicon-o-paper-clip')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Post $record) => $record->pin())
                    ->visible(fn (Post $record) => ! $record->isPinned()),

                Tables\Actions\Action::make('unpin')
                    ->label('Unpin')
                    ->icon('heroicon-o-minus')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Post $record) => $record->unpin())
                    ->visible(fn (Post $record) => $record->isPinned()),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('pin')
                        ->label('Pin Selected')
                        ->icon('heroicon-o-paper-clip')
                        ->action(fn (Collection $records) => $records->each->pin())
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('unpin')
                        ->label('Unpin Selected')
                        ->icon('heroicon-o-minus')
                        ->action(fn (Collection $records) => $records->each->unpin())
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('pinned_at', 'desc')
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'news-feed' => Pages\NewsFeed::route('/news-feed'),
        ];
    }
}

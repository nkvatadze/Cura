<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'pinned_at' => $this->record->pinned_at,
        ];
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->disabled()
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('pinned_at')
                    ->label('Pinned At')
                    ->disabled()
                    ->nullable(),
            ]);
    }
}

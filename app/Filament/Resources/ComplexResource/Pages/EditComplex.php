<?php

namespace App\Filament\Resources\ComplexResource\Pages;

use App\Filament\Resources\ComplexResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComplex extends EditRecord
{
    protected static string $resource = ComplexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

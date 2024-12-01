<?php

namespace App\Filament\Resources\SpvResource\Pages;

use App\Filament\Resources\SpvResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpv extends EditRecord
{
    protected static string $resource = SpvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

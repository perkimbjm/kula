<?php

namespace App\Filament\Resources\ConsolSpvDetailResource\Pages;

use App\Filament\Resources\ConsolSpvDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsolSpvDetail extends EditRecord
{
    protected static string $resource = ConsolSpvDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

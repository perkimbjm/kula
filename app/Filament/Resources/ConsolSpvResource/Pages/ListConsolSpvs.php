<?php

namespace App\Filament\Resources\ConsolSpvResource\Pages;

use App\Filament\Resources\ConsolSpvResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsolSpvs extends ListRecords
{
    protected static string $resource = ConsolSpvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

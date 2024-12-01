<?php

namespace App\Filament\Resources\ConsolPlanResource\Pages;

use App\Filament\Resources\ConsolPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsolPlans extends ListRecords
{
    protected static string $resource = ConsolPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

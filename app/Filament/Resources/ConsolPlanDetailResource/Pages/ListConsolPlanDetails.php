<?php

namespace App\Filament\Resources\ConsolPlanDetailResource\Pages;

use App\Filament\Resources\ConsolPlanDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsolPlanDetails extends ListRecords
{
    protected static string $resource = ConsolPlanDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

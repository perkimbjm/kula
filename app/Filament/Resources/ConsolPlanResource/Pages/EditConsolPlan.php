<?php

namespace App\Filament\Resources\ConsolPlanResource\Pages;

use App\Filament\Resources\ConsolPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsolPlan extends EditRecord
{
    protected static string $resource = ConsolPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

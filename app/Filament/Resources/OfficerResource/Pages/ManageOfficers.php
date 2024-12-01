<?php

namespace App\Filament\Resources\OfficerResource\Pages;

use App\Filament\Resources\OfficerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOfficers extends ManageRecords
{
    protected static string $resource = OfficerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

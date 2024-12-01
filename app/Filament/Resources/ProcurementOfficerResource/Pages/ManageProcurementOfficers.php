<?php

namespace App\Filament\Resources\ProcurementOfficerResource\Pages;

use App\Filament\Resources\ProcurementOfficerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProcurementOfficers extends ManageRecords
{
    protected static string $resource = ProcurementOfficerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

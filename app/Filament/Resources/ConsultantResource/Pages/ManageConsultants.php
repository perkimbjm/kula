<?php

namespace App\Filament\Resources\ConsultantResource\Pages;

use App\Filament\Resources\ConsultantResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageConsultants extends ManageRecords
{
    protected static string $resource = ConsultantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\GuideResource\Pages;

use App\Filament\Resources\GuideResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGuides extends ManageRecords
{
    protected static string $resource = GuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

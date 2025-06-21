<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFacility extends ViewRecord
{
    protected static string $resource = FacilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        if ($record && $record->work) {
            $data['contract_number_display'] = $record->work->contract_number;
            $data['technical_team_display'] = $record->work->technical_team_string;
            $data['procurement_officer_display'] = $record->work->procurementOfficer?->name;
            $data['district_display'] = $record->work->district?->name;
            $data['village_display'] = $record->work->village?->name;
        }

        return $data;
    }
}

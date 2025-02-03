<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array  
    {  
        // Check if the password is provided  
        if (empty($data['password'])) {  
            // If not provided, unset the password field to prevent it from being updated  
            unset($data['password']);  
        } else {  
            // If a new password is provided, hash it  
            $data['password'] = Hash::make($data['password']);  
        }  
  
        return $data;  
    }  
}

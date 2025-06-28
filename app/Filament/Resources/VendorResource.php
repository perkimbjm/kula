<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'User Sebagai Rekanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User Rekanan')
                    ->options(function () {
                        return \App\Models\User::whereHas('roles', function ($query) {
                            $query->where('name', 'rekanan');
                        })->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('vendor_type')
                    ->label('Jenis Rekanan')
                    ->options([
                        'kontraktor' => 'Kontraktor',
                        'konsultan' => 'Konsultan',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\Select::make('contractor_id')
                    ->label('Nama Kontraktor')
                    ->options(\App\Models\Contractor::pluck('name', 'id'))
                    ->visible(fn ($get) => $get('vendor_type') === 'kontraktor')
                    ->required(fn ($get) => $get('vendor_type') === 'kontraktor'),
                Forms\Components\Select::make('consultant_id')
                    ->label('Nama Konsultan')
                    ->options(\App\Models\Consultant::pluck('name', 'id'))
                    ->visible(fn ($get) => $get('vendor_type') === 'konsultan')
                    ->required(fn ($get) => $get('vendor_type') === 'konsultan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('vendor_type')->label('Jenis Rekanan')->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('contractor.name')->label('Nama Kontraktor')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('consultant.name')->label('Nama Konsultan')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}

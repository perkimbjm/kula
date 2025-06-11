<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConstructionResource\Pages;
use App\Filament\Resources\ConstructionResource\RelationManagers;
use App\Models\Construction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConstructionResource extends Resource
{
    protected static ?string $model = Construction::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-office-2';

    protected static ?string $navigationGroup = 'Manajemen PBJ';

    protected static ?string $label = 'Paket Fisik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('oe')
                    ->prefix('Rp.')
                    ->label('HPS')
                    ->numeric(),
                Forms\Components\TextInput::make('bid_value')
                    ->prefix('Rp.')
                    ->label('Nilai Penawaran')
                    ->numeric(),
                Forms\Components\TextInput::make('correction_value')
                    ->prefix('Rp.')
                    ->label('Aritmatik')
                    ->numeric(),
                Forms\Components\TextInput::make('nego_value')
                    ->prefix('Rp.')
                    ->label('Penawaran')
                    ->numeric(),
                Forms\Components\Select::make('contractor_id')
                    ->label('Kontraktor')
                    ->relationship('contractor', 'name')
                    ->required(),
                    Forms\Components\DatePicker::make('invite_date')
                    ->label('Tanggal Undangan')
                    ->required(),
                Forms\Components\DatePicker::make('evaluation_date')
                    ->label('Tanggal Evaluasi')
                    ->required(),
                Forms\Components\DatePicker::make('nego_date')
                    ->label('Tanggal Penawaran')
                    ->required(),
                Forms\Components\DatePicker::make('BAHPL_date')
                    ->label('Tanggal BAHPL')
                    ->required(),
                Forms\Components\DatePicker::make('sppbj_date')
                    ->label('Tanggal SPPBJ')
                    ->required(),
                Forms\Components\DatePicker::make('spk_date')
                    ->label('Tanggal SPK')
                    ->required(),
                Forms\Components\TextInput::make('account_type')
                    ->label('Sumber Dana')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('program')
                    ->label('Paket')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->label('Lama Pelaksanaan (hari kalender)')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name')
                    ->required(),
                Forms\Components\TextInput::make('location')
                    ->label('Lokasi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('consultant_id')
                    ->label('Konsultan')
                    ->relationship('consultant', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('procurement_officer_id')
                    ->label('Pejabat Pengadaan')
                    ->relationship('procurementOfficer', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('oe')
                    ->label('HPS')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bid_value')
                    ->label('Nilai Penawaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('correction_value')
                    ->label('Aritmatik')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->label('Penawaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contractor.name')
                    ->label('Kontraktor')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('invite_date')
                    ->label('Tanggal Undangan')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation_date')
                    ->label('Tanggal Evaluasi')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_date')
                    ->label('Tanggal Penawaran')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('BAHPL_date')
                    ->label('Tanggal BAHPL')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sppbj_date')
                    ->label('Tanggal SPPBJ')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('spk_date')
                    ->label('Tanggal SPK')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_type')
                    ->label('Jenis Akun')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program')
                    ->label('Paket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Lama Pelaksanaan (hari kalender)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('consultant.name')
                    ->label('Konsultan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('procurementOfficer.name')
                    ->label('Pejabat Pengadaan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListConstructions::route('/'),
            'create' => Pages\CreateConstruction::route('/create'),
            'edit' => Pages\EditConstruction::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractorResource\Pages;
use App\Filament\Resources\ContractorResource\RelationManagers;
use App\Models\Contractor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractorResource extends Resource
{
    protected static ?string $model = Contractor::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Manajemen PBJ';

    protected static ?string $label = 'Kontraktor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Penyedia Jasa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('akta')
                    ->label('No. Akta Terakhir')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('founding_date')
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\TextInput::make('notary')
                    ->label('Notaris')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Alamat Penyedia')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('npwp')
                    ->label('NPWP')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('leader')
                    ->label('Nama Direktur')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('bank')
                    ->label('Bank')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_number')
                    ->label('Nomor Rekening')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_holder')
                    ->label('Nama Nasabah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('header_scan')
                    ->label('Kop Surat')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->directory('kontraktor/kop')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('account_scan')
                    ->label('Scan Rekening')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->directory('kontraktor/rek')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('npwp_scan')
                    ->label('Scan NPWP')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->directory('kontraktor/npwp')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Penyedia Jasa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('akta')
                    ->label('No. Akta Terakhir'),
                Tables\Columns\TextColumn::make('founding_date')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('notary')
                    ->label('Notaris')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat Penyedia'),
                Tables\Columns\TextColumn::make('npwp')
                    ->label('NPWP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leader')
                    ->label('Nama Direktur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank')
                    ->label('Bank')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->label('Nomor Rekening')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_holder')
                    ->label('Nama Nasabah')
                    ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContractors::route('/'),
        ];
    }
}

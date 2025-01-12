<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsolPlanResource\Pages;
use App\Filament\Resources\ConsolPlanResource\RelationManagers;
use App\Models\ConsolPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsolPlanResource extends Resource
{
    protected static ?string $model = ConsolPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Manajemen PBJ';

    protected static ?string $label = 'Konsolidasi Paket Perencanaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')
                    ->label('Tahun')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('procurement_officer_id')
                    ->label('Pejabat Pengadaan')
                    ->relationship('procurementOfficer', 'name')
                    ->required(),
                Forms\Components\TextInput::make('bid_value')
                    ->label('Nilai Penawaran')
                    ->numeric(),
                Forms\Components\TextInput::make('correction_value')
                    ->label('Koreksi Aritmatik')
                    ->numeric(),
                Forms\Components\TextInput::make('nego_value')
                    ->label('Harga Nego')
                    ->numeric(),
                Forms\Components\Select::make('consultant_id')
                    ->label('Konsultan')
                    ->relationship('consultant', 'name')
                    ->searchable()
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
                    ->label('Kode Rekening')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('program')
                    ->label('Kegiatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->label('Lama Pelaksanaan (hari kalender)')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('procurementOfficer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bid_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('correction_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultant.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invite_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('BAHPL_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sppbj_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('spk_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
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
            'index' => Pages\ListConsolPlans::route('/'),
            'create' => Pages\CreateConsolPlan::route('/create'),
            'edit' => Pages\EditConsolPlan::route('/{record}/edit'),
        ];
    }
}

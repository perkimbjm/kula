<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'Manajemen PBJ';

    protected static ?string $label = 'Perencanaan Biasa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('NAMA PAKET')
                    ->schema([
                        Forms\Components\TextInput::make('contract_number')
                            ->label('Nomor Kontrak')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('program')
                            ->label('Paket')
                            ->required()
                            ->maxLength(255),
                    ]),

                Forms\Components\Fieldset::make('PROSES PEMILIHAN')
                    ->schema([
                        Forms\Components\Select::make('procurement_officer_id')
                            ->label('Pejabat Pengadaan')
                            ->relationship('procurementOfficer', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('duration')
                            ->label('Waktu Pelaksanaan (hari)')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('oe')
                            ->label('HPS')
                            ->prefix('Rp.')
                            ->numeric(),
                        Forms\Components\TextInput::make('bid_value')
                            ->label('Penawaran')
                            ->prefix('Rp.')
                            ->numeric(),
                        Forms\Components\TextInput::make('correction_value')
                            ->label('Aritmatik')
                            ->prefix('Rp.')
                            ->numeric(),
                        Forms\Components\TextInput::make('nego_value')
                            ->label('Harga SPK')
                            ->prefix('Rp.')
                            ->numeric(),
                        Forms\Components\Select::make('consultant_id')
                            ->label('Penyedia/Perusahaan')
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
                            ->label('Tanggal Negosiasi')
                            ->required(),
                        Forms\Components\DatePicker::make('BAHPL_date')
                            ->label('Tanggal BA-HPL')
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
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun')
                            ->required()
                            ->numeric()
                            ->default(date('Y')),
                    ]),

                Forms\Components\Fieldset::make('PROSES PEMBAYARAN')
                    ->schema([
                        Forms\Components\TextInput::make('addendum_number')
                            ->label('Nomor Addendum')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Tanggal'),
                        Forms\Components\TextInput::make('payment_value')
                            ->label('Nilai')
                            ->prefix('Rp.')
                            ->numeric(),
                        Forms\Components\TextInput::make('ba_lkpp')
                            ->label('BA LKPP')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // NAMA PAKET
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('Nomor Kontrak')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program')
                    ->label('Paket')
                    ->searchable(),

                // PROSES PEMILIHAN
                Tables\Columns\TextColumn::make('procurementOfficer.name')
                    ->label('Pejabat Pengadaan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Waktu Pelaksanaan')
                    ->suffix(' hari')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('oe')
                    ->label('HPS')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bid_value')
                    ->label('Penawaran')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('correction_value')
                    ->label('Aritmatik')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->label('Harga SPK')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultant.name')
                    ->label('Penyedia/Perusahaan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invite_date')
                    ->label('Tanggal Undangan')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('evaluation_date')
                    ->label('Tanggal Evaluasi')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nego_date')
                    ->label('Tanggal Negosiasi')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('BAHPL_date')
                    ->label('Tanggal BA-HPL')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sppbj_date')
                    ->label('Tanggal SPPBJ')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('spk_date')
                    ->label('Tanggal SPK')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('account_type')
                    ->label('Sumber Dana')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),

                // PROSES PEMBAYARAN
                Tables\Columns\TextColumn::make('addendum_number')
                    ->label('Nomor Addendum')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_value')
                    ->label('Nilai')
                    ->prefix('Rp.')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ba_lkpp')
                    ->label('BA LKPP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // System columns
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
                Tables\Filters\SelectFilter::make('year')
                    ->options(function () {
                        return collect(range(date('Y') - 5, date('Y') + 1))
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray();
                    })
                    ->label('Tahun'),
                Tables\Filters\SelectFilter::make('procurement_officer')
                    ->relationship('procurementOfficer', 'name')
                    ->label('Pejabat Pengadaan'),
                Tables\Filters\SelectFilter::make('consultant')
                    ->relationship('consultant', 'name')
                    ->label('Konsultan'),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}

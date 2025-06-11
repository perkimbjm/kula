<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Work;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enums\WorkStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Imports\WorkImporter;
use App\Tables\Columns\ProgressColumn;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\FilteredWorkExport;
use App\Filament\Resources\WorkResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WorkResource\RelationManagers;


class WorkResource extends Resource
{
    protected static ?string $model = Work::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'Proyek';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Detail Kontrak')->schema([
                    Forms\Components\TextInput::make('year')
                        ->label('Tahun')
                        ->required()
                        ->numeric()
                        ->default(date('Y')),
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Paket')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('contract_date')
                        ->label('Tanggal Kontrak'),
                    Forms\Components\TextInput::make('contract_number')
                        ->label('Nomor Kontrak')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('contract_value')
                        ->label('Nilai Kontrak')
                        ->prefix('Rp.')
                        ->numeric(),
                    Forms\Components\DatePicker::make('cutoff')
                        ->label('Tanggal Cutoff/Selesai'),
                ]),

                Forms\Components\Fieldset::make('Penyedia')->schema([
                    Forms\Components\Select::make('contractor_id')
                        ->label('Kontraktor')
                        ->searchable()
                        ->relationship('contractor', 'name'),
                    Forms\Components\Select::make('consultant_id')
                        ->label('Konsultan Perencana')
                        ->searchable()
                        ->relationship('consultant', 'name')
                        ->required(),
                    Forms\Components\Select::make('supervisor_id')
                        ->label('Konsultan Pengawas')
                        ->searchable()
                        ->relationship('supervisor', 'name'),
                ]),
                Forms\Components\Fieldset::make('Realisasi Fisik dan Keuangan')->schema([
                Forms\Components\TextInput::make('progress')
                    ->label('Progres Pekerjaan Fisik')
                    ->suffix('%')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->default(0),
                Forms\Components\Select::make('status')
                    ->label('Status Pelaksanaan')
                    ->enum(WorkStatus::class)
                    ->options(WorkStatus::class)
                    ->required()
                    ->default('belum_kontrak'),
                Forms\Components\TextInput::make('paid')
                    ->label('Jumlah Terbayar')
                    ->prefix('Rp.')
                    ->numeric(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contract_date')
                    ->label('Tanggal Kontrak')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cutoff')
                    ->label('Tanggal Cutoff/Selesai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('Nomor Kontrak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor.name')
                    ->label('Kontraktor')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultant.name')
                    ->label('Konsultan Perencana')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->label('Konsultan Pengawas')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contract_value')
                    ->label('Nilai Kontrak')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                ProgressColumn::make('progress')
                    ->label('Progres Pekerjaan Fisik')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Pelaksanaan')
                    ->formatStateUsing(fn($state): string => Str::headline($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('paid')
                    ->label('Jumlah Terbayar')
                    ->prefix('Rp.')
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
                Tables\Filters\SelectFilter::make('status')
                    ->options(WorkStatus::class)
                    ->label('Status Pelaksanaan')
                    ->default(null),
                Tables\Filters\SelectFilter::make('contractor')
                    ->relationship('contractor', 'name')
                    ->label('Kontraktor'),
                Tables\Filters\Filter::make('cutoff')
                    ->form([
                        Forms\Components\DatePicker::make('contract_from')->label('Kontrak Dari'),
                        Forms\Components\DatePicker::make('contract_until')->label('Kontrak Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['contract_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('contract_date', '>=', $date),
                            )
                            ->when(
                                        $data['contract_until'],
                                        fn (Builder $query, $date): Builder => $query->whereDate('cutoff', '<=', $date),
                                    );
                                })
                            ])
            ->headerActions([
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
            'index' => Pages\ListWorks::route('/'),
            'create' => Pages\CreateWork::route('/create'),
            'edit' => Pages\EditWork::route('/{record}/edit'),
        ];
    }

    public function importWorks(array $data): void
    {
        WorkImporter::make($data['file'])->import();
    }

}

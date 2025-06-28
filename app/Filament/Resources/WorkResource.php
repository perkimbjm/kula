<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Work;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Imports\WorkImporter;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\FilteredWorkExport;
use App\Filament\Resources\WorkResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WorkResource\RelationManagers;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Facades\Auth;


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
                Forms\Components\Fieldset::make('INFORMASI')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Paket')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('contract_number')
                        ->label('Nomor Kontrak')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('district_id')
                        ->label('Kecamatan')
                        ->preload()
                        ->required()
                        ->searchable()
                        ->relationship('district', 'name')
                        ->reactive(),
                    Forms\Components\Select::make('village_id')
                        ->label('Desa')
                        ->required()
                        ->searchable()
                        ->relationship('village', 'name')
                        ->options(function ($get) {
                            $districtId = $get('district_id');
                            return $districtId
                                ? \App\Models\District::find($districtId)->villages->pluck('name', 'id')
                                : [];
                        }),
                    Forms\Components\TextInput::make('rt')
                        ->label('RT')
                        ->required()
                        ->maxLength(10),
                    Forms\Components\TextInput::make('length')
                        ->label('Panjang (m)')
                        ->required()
                        ->numeric()
                        ->suffix('m'),
                    Forms\Components\TextInput::make('width')
                        ->label('Lebar (m)')
                        ->numeric()
                        ->suffix('m'),
                    Forms\Components\TextInput::make('phone')
                        ->label('Telepon')
                        ->required()
                        ->tel()
                        ->maxLength(15),
                    Forms\Components\TextInput::make('construction_type')
                        ->label('Konstruksi')
                        ->required()
                        ->maxLength(255),
                ]),


                Forms\Components\Fieldset::make('Koordinat')->schema([
                    Forms\Components\TextInput::make('coordinate_lat')
                        ->label('Latitude')
                        ->placeholder('Contoh: -7.250445')
                        ->numeric()
                        ->step('any')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            if ($state) {
                                $set('location', [
                                    'lat' => (float) $state,
                                    'lng' => (float) ($get('coordinate_lng') ?? 0),
                                ]);
                            }
                        }),
                    Forms\Components\TextInput::make('coordinate_lng')
                        ->label('Longitude')
                        ->placeholder('Contoh: 112.768845')
                        ->numeric()
                        ->step('any')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            if ($state) {
                                $set('location', [
                                    'lat' => (float) ($get('coordinate_lat') ?? 0),
                                    'lng' => (float) $state,
                                ]);
                            }
                        }),

                    Map::make('location')
                        ->label('Lokasi Proyek')
                        ->columnSpanFull()
                        ->defaultLocation(latitude: -2.3357594, longitude: 115.460096)
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, ?array $state): void {
                            if ($state && isset($state['lat']) && isset($state['lng'])) {
                                $set('coordinate_lat', $state['lat']);
                                $set('coordinate_lng', $state['lng']);
                            }
                        })
                        ->afterStateHydrated(function ($state, $record, Set $set): void {
                            if ($record && isset($record->coordinate_lat) && isset($record->coordinate_lng)) {
                                $set('location', [
                                    'lat' => $record->coordinate_lat,
                                    'lng' => $record->coordinate_lng
                                ]);
                            }
                        })
                        ->extraStyles([
                            'min-height: 65vh',
                        ])
                        ->liveLocation(false)
                        ->showMarker()
                        ->markerColor("#22c55eff")
                        ->showFullscreenControl()
                        ->showZoomControl()
                        ->draggable()
                        ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                        ->zoom(15)
                        ->detectRetina()
                        ->showMyLocationButton(),
                ]),


                Forms\Components\Fieldset::make('PROSES PEMILIHAN')->schema([
                    Forms\Components\TextInput::make('account_code')
                        ->label('Kode Rekening')
                        ->maxLength(50),
                    Forms\Components\TextInput::make('program')
                        ->label('Program')
                        ->maxLength(255),
                    Forms\Components\Select::make('technical_team')
                        ->label('Tim Teknis')
                        ->multiple()
                        ->relationship('officers', 'name')
                        ->preload()
                        ->searchable()
                        ->placeholder('Pilih Tim Teknis'),
                    Forms\Components\Select::make('procurement_officer_id')
                        ->label('Pejabat Pengadaan')
                        ->searchable()
                        ->preload()
                        ->relationship('procurementOfficer', 'name'),
                    Forms\Components\TextInput::make('duration')
                        ->label('Masa (hari)')
                        ->numeric()
                        ->suffix('hari'),
                    Forms\Components\TextInput::make('source')
                        ->label('Sumber')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('year')
                        ->label('Tahun')
                        ->required()
                        ->numeric()
                        ->default(date('Y')),
                        Forms\Components\TextInput::make('hps')
                        ->label('HPS')
                        ->prefix('Rp.')
                        ->numeric(),
                    Forms\Components\TextInput::make('bid_value')
                        ->label('Nilai Penawaran')
                        ->prefix('Rp.')
                        ->numeric(),
                    Forms\Components\TextInput::make('correction_value')
                        ->label('Koreksi Aritmatik')
                        ->prefix('Rp.')
                        ->numeric(),
                    Forms\Components\TextInput::make('nego_value')
                        ->label('Harga Nego')
                        ->prefix('Rp.')
                        ->numeric(),
                    Forms\Components\DatePicker::make('invite_date')
                        ->label('Tanggal Undangan'),
                    Forms\Components\DatePicker::make('evaluation_date')
                        ->label('Tanggal Evaluasi'),
                    Forms\Components\DatePicker::make('nego_date')
                        ->label('Tanggal Negosiasi'),
                    Forms\Components\DatePicker::make('bahpl_date')
                        ->label('Tanggal BA-HPL'),
                    Forms\Components\DatePicker::make('sppbj_date')
                        ->label('Tanggal SPPBJ'),
                    Forms\Components\DatePicker::make('spk_date')
                        ->label('Tanggal SPK'),


                ]),

                Forms\Components\Fieldset::make('PENYEDIA')->schema([
                    Forms\Components\Select::make('contractor_id')
                        ->label('Kontraktor')
                        ->required()
                        ->searchable()
                        ->relationship('contractor', 'name'),
                    Forms\Components\Select::make('consultant_id')
                        ->label('Konsultan Perencana')
                        ->required()
                        ->searchable()
                        ->relationship('consultant', 'name'),
                    Forms\Components\Select::make('supervisor_id')
                        ->label('Konsultan Pengawas')
                        ->required()
                        ->searchable()
                        ->relationship('supervisor', 'name'),
                ]),

                Forms\Components\Fieldset::make('PEMBAYARAN')->schema([
                    Forms\Components\TextInput::make('add_number')
                        ->label('Nomor Addendum')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('addendum_date')
                        ->label('Tanggal Addendum'),
                    Forms\Components\TextInput::make('addendum_value')
                        ->label('Nilai Addendum')
                        ->prefix('Rp.')
                        ->numeric(),
                    Forms\Components\TextInput::make('completion_letter')
                        ->label('Surat Keterangan Selesai')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('completion_date')
                        ->label('Tanggal Surat Keterangan Selesai'),
                    Forms\Components\DatePicker::make('pho_date')
                        ->label('Tanggal PHO'),
                        Forms\Components\TextInput::make('advance_bap_number')
                        ->label('No BAP Uang Muka')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('advance_guarantee_number')
                        ->label('No. Jaminan Uang Muka')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('advance_guarantor')
                        ->label('Penjamin Uang Muka')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('advance_guarantee_date')
                        ->label('Tanggal Jaminan Uang Muka'),
                    Forms\Components\TextInput::make('advance_value')
                        ->label('Nilai Uang Muka')
                        ->prefix('Rp.')
                        ->numeric(),
                    Forms\Components\DatePicker::make('advance_payment_date')
                        ->label('Tanggal Pembayaran Uang Muka'),
                        Forms\Components\TextInput::make('final_bap_number')
                        ->label('No BAP Pelunasan')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('maintenance_guarantee_number')
                        ->label('No. Jaminan Pemeliharaan')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('final_guarantor')
                        ->label('Penjamin Pelunasan')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('final_guarantee_date')
                        ->label('Tanggal Jaminan'),
                    Forms\Components\TextInput::make('final_guarantee_value')
                        ->label('Nilai Jaminan')
                        ->prefix('Rp.')
                        ->numeric(),
                    Forms\Components\DatePicker::make('final_payment_date')
                        ->label('Tanggal Pembayaran'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->selectable()
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                // Jika user memiliki role 'rekanan', filter berdasarkan vendor_id
                if ($user && $user->hasRole('rekanan')) {
                    $vendor = $user->vendor()->first();

                    if ($vendor) {
                        if ($vendor->vendor_type === 'kontraktor' && $vendor->contractor_id) {
                            $query->where('contractor_id', $vendor->contractor_id);
                        } elseif ($vendor->vendor_type === 'konsultan' && $vendor->consultant_id) {
                            $query->where('consultant_id', $vendor->consultant_id);
                        }
                    }
                }

                return $query->with([
                    'district:id,name',
                    'village:id,name',
                    'contractor:id,name',
                    'consultant:id,name',
                    'supervisor:id,name',
                    'officers:id,name',
                    'procurementOfficer:id,name'
                ]);
            })
            ->defaultPaginationPageOption(25)
            ->persistFiltersInSession()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('No. Kontrak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('village.name')
                    ->label('Desa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('length')
                    ->label('Panjang')
                    ->suffix(' m')
                    ->numeric(),
                Tables\Columns\TextColumn::make('construction_type')
                    ->label('Konstruksi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rt')
                    ->label('RT')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('width')
                    ->label('Lebar')
                    ->suffix(' m')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('coordinate_lat')
                    ->label('Latitude')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('coordinate_lng')
                    ->label('Longitude')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('account_code')
                    ->label('Kode Rekening')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program')
                    ->label('Program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')
                    ->label('Sumber')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->label('Harga Nego')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contractor.name')
                    ->label('Kontraktor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('officers.name')
                    ->label('Tim Teknis')
                    ->formatStateUsing(fn($record) => $record->officers ? $record->officers->pluck('name')->implode(', ') : '-')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('procurementOfficer.name')
                    ->label('Pejabat Pengadaan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Masa')
                    ->suffix(' hari')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('hps')
                    ->label('HPS')
                    ->prefix('Rp.')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bid_value')
                    ->label('Nilai Penawaran')
                    ->prefix('Rp.')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('correction_value')
                    ->label('Koreksi Aritmatik')
                    ->prefix('Rp.')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->label('Konsultan Pengawas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('consultant.name')
                    ->label('Konsultan Perencana')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('invite_date')
                    ->label('Undangan')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('evaluation_date')
                    ->label('Evaluasi')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nego_date')
                    ->label('Negosiasi')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bahpl_date')
                    ->label('BA-HPL')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sppbj_date')
                    ->label('SPPBJ')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('spk_date')
                    ->label('SPK')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('addendum_value')
                    ->label('Nilai Addendum')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('add_number')
                    ->label('Nomor Addendum')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('addendum_date')
                    ->label('Tanggal Addendum')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('completion_letter')
                    ->label('Surat Keterangan Selesai')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('completion_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pho_date')
                    ->label('Tanggal PHO')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('advance_bap_number')
                    ->label('No BAP Uang Muka')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('advance_guarantee_number')
                    ->label('No. Jaminan Uang Muka')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('advance_guarantor')
                    ->label('Penjamin Uang Muka')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('advance_guarantee_date')
                    ->label('Tanggal Jaminan Uang Muka')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('advance_value')
                    ->label('Nilai Uang Muka')
                    ->prefix('Rp.')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('advance_payment_date')
                    ->label('Tanggal Pembayaran Uang Muka')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('final_bap_number')
                    ->label('No BAP Pelunasan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('maintenance_guarantee_number')
                    ->label('No. Jaminan Pemeliharaan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('final_guarantor')
                    ->label('Penjamin Pelunasan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('final_guarantee_date')
                    ->label('Tanggal Jaminan')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('final_guarantee_value')
                    ->label('Nilai Jaminan')
                    ->prefix('Rp.')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('final_payment_date')
                    ->label('Tanggal Pembayaran')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('contractor')
                    ->relationship('contractor', 'name')
                    ->label('Kontraktor')
                    ->preload()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('district')
                    ->relationship('district', 'name')
                    ->label('Kecamatan')
                    ->preload()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('year')
                    ->options(function () {
                        return Cache::remember('work_years', 3600, function () {
                            return Work::query()
                                ->whereNotNull('year')
                                ->distinct()
                                ->pluck('year', 'year')
                                ->filter()
                                ->toArray();
                        });
                    })
                    ->label('Tahun'),
                Tables\Filters\SelectFilter::make('account_code')
                    ->options(function () {
                        return Cache::remember('work_account_codes', 3600, function () {
                            return Work::query()
                                ->whereNotNull('account_code')
                                ->where('account_code', '!=', '')
                                ->distinct()
                                ->pluck('account_code', 'account_code')
                                ->filter()
                                ->toArray();
                        });
                    })
                    ->label('Kode Rekening'),
                Tables\Filters\SelectFilter::make('program')
                    ->options(function () {
                        return Cache::remember('work_programs', 3600, function () {
                            return Work::query()
                                ->whereNotNull('program')
                                ->where('program', '!=', '')
                                ->distinct()
                                ->pluck('program', 'program')
                                ->filter()
                                ->toArray();
                        });
                    })
                    ->label('Program'),
            ])
            ->headerActions([
                // Header actions dipindahkan ke ListWorks page
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



}

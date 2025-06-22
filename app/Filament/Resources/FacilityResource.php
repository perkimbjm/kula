<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Work;
use App\Models\District;
use App\Models\Facility;
use App\Enums\ProgressStatus;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Section;
use App\Tables\Columns\ProgressColumn;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\FacilityExporter;
use Dotswan\MapPicker\Fields\Map;
use App\Filament\Resources\FacilityResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FacilityResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Log;


class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Informasi Paket Pekerjaan')
                ->schema([
                    Forms\Components\Select::make('work_id')
                        ->label('Nama Paket')
                        ->relationship('work', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            if ($state) {
                                $work = Work::find($state);
                                if ($work) {
                                    $set('contract_number_display', $work->contract_number);
                                    $set('technical_team_display', $work->technical_team_string);
                                    $set('procurement_officer_display', $work->procurementOfficer?->name);
                                    $set('district_display', $work->district?->name);
                                    $set('village_display', $work->village?->name);
                                }
                            }
                        })
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('contract_number_display')
                        ->label('No. Kontrak')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Pilih nama paket terlebih dahulu'),

                    Forms\Components\TextInput::make('technical_team_display')
                        ->label('Tim Teknis')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Pilih nama paket terlebih dahulu'),

                    Forms\Components\TextInput::make('procurement_officer_display')
                        ->label('Pejabat Pengadaan')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Pilih nama paket terlebih dahulu'),

                    Forms\Components\TextInput::make('district_display')
                        ->label('Kecamatan')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Pilih nama paket terlebih dahulu'),

                    Forms\Components\TextInput::make('village_display')
                        ->label('Desa')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Pilih nama paket terlebih dahulu'),
                ])
                ->columns(2),

            Section::make('Detail Lokasi')
                ->schema([
                    Forms\Components\TextInput::make('rt')
                        ->label('RT')
                        ->maxLength(10),

                    Forms\Components\TextInput::make('phone')
                        ->label('Telepon')
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\TextInput::make('length')
                        ->label('Panjang')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('m'),

                    Forms\Components\TextInput::make('width')
                        ->label('Lebar')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('m'),

                    Forms\Components\TextInput::make('construct_type')
                        ->label('Konstruksi')
                        ->maxLength(255)
                        ->columnSpan(2),

                    // Hidden fields untuk MapPicker (sesuai dokumentasi)
                    TextInput::make('latitude')
                        ->hiddenLabel()
                        ->hidden(),
                    TextInput::make('longitude')
                        ->hiddenLabel()
                        ->hidden(),

                    TextInput::make('lat')
                        ->label('Latitude')
                        ->placeholder('Contoh: -2.33668')
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get, $state, $livewire): void {
                             $lng = $get('lng');
                             if ($state && $lng && is_numeric($state) && is_numeric($lng)) {
                                 $set('location', ['lat' => (string)$state, 'lng' => (string)$lng]);
                                 $set('latitude', (string)$state);  // Sync ke hidden field
                                 $set('longitude', (string)$lng);   // Sync ke hidden field
                                 $livewire->dispatch('refreshMap');
                             }
                         })
                        ->rules(['nullable', 'numeric', 'between:-90,90']),

                    TextInput::make('lng')
                        ->label('Longitude')
                        ->placeholder('Contoh: 115.46028')
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get, $state, $livewire): void {
                             $lat = $get('lat');
                             if ($state && $lat && is_numeric($state) && is_numeric($lat)) {
                                 $set('location', ['lat' => (string)$lat, 'lng' => (string)$state]);
                                 $set('latitude', (string)$lat);    // Sync ke hidden field
                                 $set('longitude', (string)$state); // Sync ke hidden field
                                 $livewire->dispatch('refreshMap');
                             }
                         })
                        ->rules(['nullable', 'numeric', 'between:-180,180']),

                    Map::make('location')
                        ->label('Pilih Lokasi di Peta')
                        ->columnSpanFull()
                        ->defaultLocation(latitude: -2.33668, longitude: 115.46028)
                        ->afterStateUpdated(function (Set $set, ?array $state): void {
                            // Debug: Log state untuk troubleshooting
                            Log::info('FacilityResource MapPicker afterStateUpdated:', $state ?? []);

                            if ($state && isset($state['lat']) && isset($state['lng'])) {
                                // Cast ke string untuk konsistensi dengan database
                                $lat = (string) $state['lat'];
                                $lng = (string) $state['lng'];

                                // Set ke field yang visible
                                $set('lat', $lat);
                                $set('lng', $lng);

                                // Set ke hidden fields untuk MapPicker
                                $set('latitude', $lat);
                                $set('longitude', $lng);

                                // Debug: Log koordinat yang di-set
                                Log::info('FacilityResource Setting coordinates:', ['lat' => $lat, 'lng' => $lng]);
                            }
                        })
                        ->afterStateHydrated(function ($state, $record, Set $set): void {
                            if ($record && $record->lat && $record->lng) {
                                $set('location', [
                                    'lat' => $record->lat,
                                    'lng' => $record->lng
                                ]);
                                // Sync ke hidden fields juga
                                $set('latitude', $record->lat);
                                $set('longitude', $record->lng);
                            }
                        })
                        ->showMarker()
                        ->draggable(true)
                        ->clickable(true)
                        ->showMyLocationButton(true)
                        ->showZoomControl()
                        ->showFullscreenControl()
                        ->liveLocation(false, false, 5000)
                        ->zoom(15)
                        ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png"),
                ])
                ->columns(2),

            Section::make('Status dan Progress')
                ->schema([
                    Forms\Components\Select::make('progress_status')
                        ->label('Progress Status')
                        ->options(ProgressStatus::class)
                        ->required(),

                    Forms\Components\TextInput::make('real_1')
                        ->label('Progress Minggu 1')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01)
                        ->suffix('%'),

                    Forms\Components\TextInput::make('real_2')
                        ->label('Progress Minggu 2')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01)
                        ->suffix('%'),

                    Forms\Components\TextInput::make('real_3')
                        ->label('Progress Minggu 3')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01)
                        ->suffix('%'),

                    Forms\Components\TextInput::make('real_4')
                        ->label('Progress Minggu 4')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01)
                        ->suffix('%'),

                    Forms\Components\TextInput::make('real_5')
                        ->label('Progress Minggu 5')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01)
                        ->suffix('%'),

                    Forms\Components\TextInput::make('real_6')
                        ->label('Progress Minggu 6')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01)
                        ->suffix('%'),

                    Forms\Components\Textarea::make('note')
                        ->label('Catatan Konsultan')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Dokumentasi Foto')
                ->schema([
                    Forms\Components\Group::make([
                    Forms\Components\FileUpload::make('photo_0')
                            ->label('Foto 1 (File)')
                        ->image()
                            ->imageEditor()
                            ->directory('facility/photos')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('photo_0_url')
                            ->label('Foto 1 (URL Alternatif)')
                            ->url()
                            ->placeholder('https://example.com/photo1.jpg')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                    Forms\Components\FileUpload::make('photo_50')
                            ->label('Foto 2 (File)')
                        ->image()
                            ->imageEditor()
                            ->directory('facility/photos')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('photo_50_url')
                            ->label('Foto 2 (URL Alternatif)')
                            ->url()
                            ->placeholder('https://example.com/photo2.jpg')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                    Forms\Components\FileUpload::make('photo_100')
                            ->label('Foto 3 (File)')
                        ->image()
                            ->imageEditor()
                            ->directory('facility/photos')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('photo_100_url')
                            ->label('Foto 3 (URL Alternatif)')
                            ->url()
                            ->placeholder('https://example.com/photo3.jpg')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                    Forms\Components\FileUpload::make('photo_pho')
                            ->label('Foto 4 (File)')
                        ->image()
                            ->imageEditor()
                            ->directory('facility/photos')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('photo_pho_url')
                            ->label('Foto 4 (URL Alternatif)')
                            ->url()
                            ->placeholder('https://example.com/photo4.jpg')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),
                ])
                ->columns(1),

            Section::make('File Teknis')
                ->schema([
                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('shop_drawing')
                            ->label('Shop Drawing (File)')
                            ->directory('facility/drawings')
                            ->acceptedFileTypes(['application/pdf', 'application/zip', 'application/x-zip-compressed', '.dwg'])
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('shop_drawing_url')
                            ->label('Shop Drawing (URL)')
                            ->url()
                            ->placeholder('https://example.com/shop_drawing.pdf')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('asbuilt_drawing')
                            ->label('Asbuilt Drawing (File)')
                            ->directory('facility/drawings')
                            ->acceptedFileTypes(['application/pdf', 'application/zip', 'application/x-zip-compressed', '.dwg'])
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('asbuilt_drawing_url')
                            ->label('Asbuilt Drawing (URL)')
                            ->url()
                            ->placeholder('https://example.com/asbuilt_drawing.pdf')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('rab')
                            ->label('RAB (File)')
                            ->directory('facility/documents')
                            ->acceptedFileTypes([
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/pdf',
                                'application/zip',
                                'application/x-zip-compressed'
                            ])
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('rab_url')
                            ->label('RAB (URL)')
                            ->url()
                            ->placeholder('https://example.com/rab.xlsx')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('file_shp')
                            ->label('File SHP (File)')
                            ->directory('facility/shp')
                            ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('file_shp_url')
                            ->label('File SHP (URL)')
                            ->url()
                            ->placeholder('https://example.com/shapefile.zip')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('laporan')
                            ->label('Laporan (Multi File)')
                        ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->maxFiles(10)
                            ->directory('facility/reports')
                            ->helperText('Dapat upload multiple file. Klik "Add files" untuk menambah file baru.')
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('laporan_url')
                            ->label('Laporan (URL)')
                            ->placeholder("https://example.com/laporan1.pdf\nhttps://example.com/laporan2.pdf")
                            ->helperText('Opsional: Masukkan URL (satu per baris) jika tidak ingin upload file')
                            ->rows(4)
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('file_konsultan_perencana')
                            ->label('File Konsultan Perencana (File)')
                            ->directory('facility/consultants')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('file_konsultan_perencana_url')
                            ->label('File Konsultan Perencana (URL)')
                            ->url()
                            ->placeholder('https://example.com/konsultan_perencana.pdf')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('file_konsultan_pengawas')
                            ->label('File Konsultan Pengawas (File)')
                            ->directory('facility/consultants')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('file_konsultan_pengawas_url')
                            ->label('File Konsultan Pengawas (URL)')
                            ->url()
                            ->placeholder('https://example.com/konsultan_pengawas.pdf')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),

                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('file_kontraktor_pelaksana')
                            ->label('File Kontraktor Pelaksana (File)')
                            ->directory('facility/contractors')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('file_kontraktor_pelaksana_url')
                            ->label('File Kontraktor Pelaksana (URL)')
                            ->url()
                            ->placeholder('https://example.com/kontraktor_pelaksana.pdf')
                            ->helperText('Opsional: Masukkan URL jika tidak ingin upload file')
                            ->columnSpan(1),
                    ])->columns(3),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('work.contract_number')
                    ->label('No. Kontrak')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('work.name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('work.technical_team_string')
                    ->label('Tim Teknis')
                    ->searchable()
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('work.procurementOfficer.name')
                    ->label('Pejabat Pengadaan')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('work.district.name')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('work.village.name')
                    ->label('Desa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rt')
                    ->label('RT')
                    ->searchable(),

                Tables\Columns\TextColumn::make('length')
                    ->label('Panjang')
                    ->suffix(' m')
                    ->numeric(2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('width')
                    ->label('Lebar')
                    ->suffix(' m')
                    ->numeric(2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('construct_type')
                    ->label('Konstruksi')
                    ->searchable()
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('lat')
                    ->label('Latitude')
                    ->formatStateUsing(fn (?string $state) => $state ? number_format((float)$state, 7, '.', '') : '-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('lng')
                    ->label('Longitude')
                    ->formatStateUsing(fn (?string $state) => $state ? number_format((float)$state, 7, '.', '') : '-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('progress_status')
                    ->label('Progress Status')
                    ->badge()
                    ->color(fn ($state) => match ($state?->value ?? null) {
                        'berjalan' => 'info',
                        'kritis' => 'warning',
                        'selesai' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state?->getLabel() ?? '-'),

                ProgressColumn::make('highest_progress')
                    ->label('Progress Tertinggi')
                    ->state(function ($record) {
                        $values = [
                            floatval($record->real_1),
                            floatval($record->real_2),
                            floatval($record->real_3),
                            floatval($record->real_4),
                            floatval($record->real_5),
                            floatval($record->real_6),
                        ];
                        $maxValue = max($values);
                        return number_format($maxValue, 2);
                    }),

                Tables\Columns\TextColumn::make('real_1')
                    ->label('Minggu 1')
                    ->suffix('%')
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('real_2')
                    ->label('Minggu 2')
                    ->suffix('%')
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('real_3')
                    ->label('Minggu 3')
                    ->suffix('%')
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('real_4')
                    ->label('Minggu 4')
                    ->suffix('%')
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('real_5')
                    ->label('Minggu 5')
                    ->suffix('%')
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('real_6')
                    ->label('Minggu 6')
                    ->suffix('%')
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('files_status')
                    ->label('Status File')
                    ->getStateUsing(function ($record) {
                        $total = $record->total_files;
                        return $total > 0 ? $total . ' file(s)/URL' : 'Belum ada file';
                    })
                    ->badge()
                    ->color(fn (string $state): string => str_contains($state, 'Belum') ? 'warning' : 'success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('work')
                    ->relationship('work', 'name')
                    ->label('Nama Paket')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('progress_status')
                    ->label('Progress Status')
                    ->options(ProgressStatus::class),

                Tables\Filters\Filter::make('district')
                    ->form([
                        Forms\Components\Select::make('district_id')
                            ->label('Kecamatan')
                            ->options(function () {
                                return \App\Models\District::pluck('name', 'id');
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['district_id'],
                            fn (Builder $query, $districtId): Builder => $query->whereHas('work', function ($q) use ($districtId) {
                                $q->where('district_id', $districtId);
                            })
                        );
                    }),

                Tables\Filters\Filter::make('village')
                    ->form([
                        Forms\Components\Select::make('village_id')
                            ->label('Desa')
                            ->options(function () {
                                return \App\Models\Village::pluck('name', 'id');
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['village_id'],
                            fn (Builder $query, $villageId): Builder => $query->whereHas('work', function ($q) use ($villageId) {
                                $q->where('village_id', $villageId);
                            })
                        );
                    }),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'view' => Pages\ViewFacility::route('/{record}'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}

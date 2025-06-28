<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Track;
use App\Models\Work;
use App\Models\Officer;
use App\Enums\TrackStatus;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\TrackExporter;
use Illuminate\Database\Eloquent\Builder;
use Dotswan\MapPicker\Fields\Map;
use App\Filament\Resources\TrackResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TrackResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TrackResource extends Resource
{
    protected static ?string $model = Track::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'Tracking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Proyek')
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
                                    $work = Work::with(['consultant', 'contractor'])->find($state);
                                    if ($work) {
                                        $set('contract_number_display', $work->contract_number);
                                        $set('consultant_display', $work->consultant?->name);
                                        $set('supervisor_display', $work->supervisor?->name);
                                        $set('contractor_display', $work->contractor?->name);
                                    }
                                }
                            })
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('contract_number_display')
                                    ->label('No. Kontrak')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder('Pilih nama paket terlebih dahulu'),

                                Forms\Components\TextInput::make('consultant_display')
                                    ->label('Perencana')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder('Pilih nama paket terlebih dahulu'),

                                Forms\Components\TextInput::make('supervisor_display')
                                    ->label('Pengawas')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder('Pilih nama paket terlebih dahulu'),

                                Forms\Components\TextInput::make('contractor_display')
                                    ->label('Pelaksana')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder('Pilih nama paket terlebih dahulu'),
                            ])
                    ])
                    ->columns(2),

                Section::make('Track Progress Fisik')
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\Checkbox::make('survei')
                                    ->label('Survei'),
                                Forms\Components\Checkbox::make('pemilihan')
                                    ->label('Pemilihan'),
                                Forms\Components\Checkbox::make('kontrak')
                                    ->label('Kontrak'),
                                Forms\Components\Checkbox::make('uang_muka')
                                    ->label('Uang Muka'),
                                Forms\Components\Checkbox::make('kritis')
                                    ->label('Kritis'),
                                Forms\Components\Checkbox::make('selesai')
                                    ->label('Selesai'),
                                Forms\Components\Checkbox::make('pho')
                                    ->label('PHO'),
                                Forms\Components\Checkbox::make('aset')
                                    ->label('Aset'),
                                Forms\Components\Checkbox::make('ppk_dinas')
                                    ->label('PPK DINAS'),
                                Forms\Components\Checkbox::make('bendahara')
                                    ->label('Bendahara'),
                                Forms\Components\Checkbox::make('pengguna_anggaran')
                                    ->label('Pengguna Anggaran'),
                                Forms\Components\Checkbox::make('keuangan')
                                    ->label('Keuangan'),
                                Forms\Components\Checkbox::make('bank')
                                    ->label('BANK'),
                                Forms\Components\Checkbox::make('laporan')
                                    ->label('Laporan'),
                            ]),

                        Forms\Components\Select::make('pemeriksa')
                            ->label('Pemeriksa')
                            ->multiple()
                            ->options(Officer::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),

                Section::make('Detail Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('panjang')
                            ->label('Panjang')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('m'),
                        Forms\Components\TextInput::make('lebar')
                            ->label('Lebar')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('m'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('lat')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step('any')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $set('location', [
                                                'lat' => (float) $state,
                                                'lng' => (float) ($get('lng') ?? 0),
                                            ]);
                                        }
                                    }),
                                Forms\Components\TextInput::make('lng')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step('any')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $set('location', [
                                                'lat' => (float) ($get('lat') ?? 0),
                                                'lng' => (float) $state,
                                            ]);
                                        }
                                    }),
                            ]),

                        Map::make('location')
                            ->label('Lokasi yang Dilaporkan')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -2.3357594, longitude: 115.460096)
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, ?array $state): void {
                                if ($state && isset($state['lat']) && isset($state['lng'])) {
                                    $set('lat', $state['lat']);
                                    $set('lng', $state['lng']);
                                }
                            })
                            ->afterStateHydrated(function ($state, $record, Set $set): void {
                                if ($record && isset($record->lat) && isset($record->lng)) {
                                    $set('location', [
                                        'lat' => $record->lat,
                                        'lng' => $record->lng
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

                Section::make('Dokumentasi')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_survey')
                            ->label('Foto Survey')
                            ->multiple()
                            ->image()
                            ->directory('tracking/survey')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('foto_pho')
                            ->label('Foto PHO')
                            ->multiple()
                            ->image()
                            ->directory('tracking/pho')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'baik' => 'Baik',
                                'dengan_perbaikan' => 'Dengan Perbaikan',
                                'kurang' => 'Kurang',
                            ])
                            ->default('baik')
                            ->required(),

                        Forms\Components\Textarea::make('catatan_tim_teknis')
                            ->label('Catatan Tim Teknis')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('lampiran')
                            ->label('Lampiran')
                            ->multiple()
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/*'])
                            ->directory('tracking/lampiran')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
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
                            $query->whereHas('work', function ($q) use ($vendor) {
                                $q->where('contractor_id', $vendor->contractor_id);
                            });
                        } elseif ($vendor->vendor_type === 'konsultan' && $vendor->consultant_id) {
                            $query->whereHas('work', function ($q) use ($vendor) {
                                $q->where('consultant_id', $vendor->consultant_id);
                            });
                        }
                    }
                }

                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('work.name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('No. Kontrak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor_name')
                    ->label('Pelaksana')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supervisor_name')
                    ->label('Pengawas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('work_year')
                    ->label('Tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('latest_progress')
                    ->label('Progress')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Belum Dimulai' => 'gray',
                        'Survei', 'Pemilihan' => 'warning',
                        'Kontrak', 'Uang Muka', 'Kritis' => 'info',
                        'Selesai', 'PHO' => 'success',
                        'Aset', 'PPK Dinas', 'Bendahara', 'Pengguna Anggaran', 'Keuangan', 'Bank', 'Laporan' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (TrackStatus $state): string => $state->getLabel())
                    ->color(fn (TrackStatus $state): string => $state->getColor()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'baik' => 'Baik',
                        'dengan_perbaikan' => 'Dengan Perbaikan',
                        'kurang' => 'Kurang',
                    ]),
                Tables\Filters\SelectFilter::make('work_id')
                    ->label('Nama Paket')
                    ->relationship('work', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('supervisor')
                    ->label('Pengawas')
                    ->options(function () {
                        return \App\Models\Consultant::pluck('name', 'id')->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas('work', function ($q) use ($value) {
                                $q->where('supervisor_id', $value);
                            })
                        );
                    }),
                Tables\Filters\SelectFilter::make('contractor')
                    ->label('Pelaksana')
                    ->options(function () {
                        return \App\Models\Contractor::pluck('name', 'id')->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas('work', function ($q) use ($value) {
                                $q->where('contractor_id', $value);
                            })
                        );
                    }),
                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(function () {
                        return Work::distinct()->pluck('year', 'year')->sort()->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas('work', function ($q) use ($value) {
                                $q->where('year', $value);
                            })
                        );
                    }),
                Tables\Filters\SelectFilter::make('latest_progress')
                    ->label('Progress')
                    ->options([
                        'Belum Dimulai' => 'Belum Dimulai',
                        'Survei' => 'Survei',
                        'Pemilihan' => 'Pemilihan',
                        'Kontrak' => 'Kontrak',
                        'Uang Muka' => 'Uang Muka',
                        'Kritis' => 'Kritis',
                        'Selesai' => 'Selesai',
                        'PHO' => 'PHO',
                        'Aset' => 'Aset',
                        'PPK Dinas' => 'PPK Dinas',
                        'Bendahara' => 'Bendahara',
                        'Pengguna Anggaran' => 'Pengguna Anggaran',
                        'Keuangan' => 'Keuangan',
                        'Bank' => 'Bank',
                        'Laporan' => 'Laporan',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!$data['value']) {
                            return $query;
                        }

                        // Filter berdasarkan latest progress
                        return $query->get()->filter(function ($record) use ($data) {
                            return $record->latest_progress === $data['value'];
                        })->pluck('id')->pipe(function ($ids) use ($query) {
                            return $query->whereIn('id', $ids);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(TrackExporter::class)
                    ->label('Export ke Excel')
                    ->color('success'),
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
            'index' => Pages\ListTracks::route('/'),
            'create' => Pages\CreateTrack::route('/create'),
            'view' => Pages\ViewTrack::route('/{record}'),
            'edit' => Pages\EditTrack::route('/{record}/edit'),
        ];
    }
}

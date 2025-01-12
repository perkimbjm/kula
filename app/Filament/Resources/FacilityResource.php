<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\District;
use App\Models\Facility;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Section;
use App\Tables\Columns\ProgressColumn; 
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\FacilityExporter;
use App\Filament\Resources\FacilityResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FacilityResource\RelationManagers;


class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'PSU';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Section::make('Penyedia Jasa')
                ->schema([
                    Forms\Components\Select::make('contractor_id')
                        ->label('Kontraktor')
                        ->relationship('contractor', 'name')
                        ->required(),
                    Forms\Components\Select::make('consultant_id')
                        ->label('Konsultan')
                        ->relationship('consultant', 'name')
                        ->required(),
                ]),

            Section::make('Lokasi')
                ->schema([
                    Forms\Components\Select::make('district_id')
                        ->label('Kecamatan')
                        ->relationship('district', 'name')
                        ->required()
                        ->reactive(),
                    Forms\Components\Select::make('village_id')
                        ->label('Kelurahan / Desa')
                        ->relationship('village', 'name')
                        ->options(function ($get) {
                            $districtId = $get('district_id');
                            return $districtId 
                                ? \App\Models\District::find($districtId)->villages->pluck('name', 'id') 
                                : [];
                        })
                        ->required(),
                ]),

            Section::make('Dimensi dan Koordinat')
                ->schema([
                    Forms\Components\TextInput::make('length')
                        ->label('Panjang')
                        ->numeric(),
                    Forms\Components\TextInput::make('width')
                        ->label('Lebar')
                        ->numeric(),
                    Forms\Components\Grid::make(2)
                        ->schema([
                        Forms\Components\TextInput::make('lat')
                                ->label('lat')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    if ($state) {
                                        $set('location', [
                                            'lat' => $state,
                                            'lng' => $get('lng') ?? 0,
                                        ]);
                                    }
                                }),
                        Forms\Components\TextInput::make('lng')
                                ->label('lng')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    if ($state) {
                                        $set('location', [
                                            'lat' => $get('lat') ?? 0,
                                            'lng' => $state,
                                        ]);
                                    }
                                }),
                        ]),
                        Map::make('location')
                            ->label('Location')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -2.3357594, longitude: 115.460096)
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, ?array $state): void {
                                $set('lat',  $state['lat']);
                                $set('lng', $state['lng']);
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
                            ->liveLocation(true, true, 120000)
                            ->showMarker()
                            ->markerColor("#22c55eff")
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->zoom(18)
                            ->detectRetina()
                            ->showMyLocationButton(),
                ]),

            Section::make('Kemajuan Realisasi')
                ->schema([
                    Forms\Components\TextInput::make('real_1')
                        ->label('Realisasi Fisik Minggu ke-1')->numeric()->minValue(0)->maxValue(100)->step(0.01)->prefix('%'),
                    Forms\Components\TextInput::make('real_2')
                        ->label('Realisasi Fisik Minggu ke-2')->numeric()->minValue(0)->maxValue(100)->step(0.01)->prefix('%'),
                    Forms\Components\TextInput::make('real_3')
                        ->label('Realisasi Fisik Minggu ke-3')->numeric()->minValue(0)->maxValue(100)->step(0.01)->prefix('%'),
                    Forms\Components\TextInput::make('real_4')
                        ->label('Realisasi Fisik Minggu ke-4')->numeric()->minValue(0)->maxValue(100)->step(0.01)->prefix('%'),
                    Forms\Components\TextInput::make('real_5')
                        ->label('Realisasi Fisik Minggu ke-5')->numeric()->minValue(0)->maxValue(100)->step(0.01)->prefix('%'),
                    Forms\Components\TextInput::make('real_6')
                        ->label('Realisasi Fisik Minggu ke-6')->numeric()->minValue(0)->maxValue(100)->step(0.01)->prefix('%'),
                    Forms\Components\TextInput::make('real_7')
                        ->label('Realisasi Fisik Minggu ke-7')->numeric()->minValue(0)->maxValue(100)->step(0.01)->prefix('%'),
                    Forms\Components\TextInput::make('real_8')
                        ->label('Realisasi Fisik Minggu ke-8')->numeric()->minValue(0)->maxValue(100)->step(0.01)->prefix('%'),
                ]),

            Section::make('Foto Realisasi')
                ->schema([
                    Forms\Components\FileUpload::make('photo_0')
                        ->label('Foto 0%')
                        ->image()
                        ->directory('facility'),
                    Forms\Components\FileUpload::make('photo_50')
                        ->label('Foto 50%')
                        ->image()
                        ->directory('facility'),
                    Forms\Components\FileUpload::make('photo_100')
                        ->label('Foto 100%')
                        ->image()
                        ->directory('facility'),
                    Forms\Components\FileUpload::make('photo_pho')
                        ->label('Foto PHO')
                        ->image()
                        ->directory('facility'),
                ]),

            Section::make('PHO')
                ->schema([
                    Forms\Components\Textarea::make('note')
                        ->label('Catatan Konsultan')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('note_pho')
                        ->label('Catatan PHO')
                        ->columnSpanFull(),
                    Forms\Components\Select::make('team')
                        ->label('Tim PHO')
                        ->multiple()
                        ->relationship('officers', 'name')
                        ->preload()
                        ->columnSpanFull()
                        ->searchable()
                        ->placeholder('Pilih Tim PHO'),
                    Forms\Components\Hidden::make('team'),
                    Forms\Components\TextInput::make('construct_type')
                        ->label('Jenis Konstruksi')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('spending_type')
                        ->label('Jenis Pembayaran')
                        ->maxLength(255),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $livewire = $table->getLivewire();

        return $table
            ->columns(
                $livewire->isGridLayout()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->defaultView('list')
            ->contentGrid(
                fn () => $livewire->isListLayout()
                    ? null
                    : [
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 4,
                    ]
            )
            ->headerActions([
                Action::make('export')
                    ->label('Ekspor ke Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->form([
                        Forms\Components\CheckboxList::make('selectedColumns')
                            ->label('Pilih Kolom')
                            ->options(collect(FacilityExporter::getColumns())->mapWithKeys(function ($column) {
                                return [$column->getName() => $column->getLabel()];
                            }))
                            ->afterStateHydrated(function (Forms\Components\CheckboxList $component) {
                                $defaultColumns = collect(FacilityExporter::getColumns())->map(function ($column) {
                                    return $column->getName();
                                })->toArray();
                                $component->state($defaultColumns); // Set default state
                            })
                            ->required(),
                    ])
                    ->action(function (array $data, $livewire) {
                        $filters = $livewire->tableFilters;
                        $selectedColumns = $data['selectedColumns'];
                
                        $export = new FacilityExporter($filters, $selectedColumns);
                
                        return Excel::download($export, 'Data PSU.xlsx');
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('contractor')
                    ->relationship('contractor', 'name')
                    ->label('Kontraktor'),
                Tables\Filters\SelectFilter::make('district_id')
                    ->relationship('district', 'name')
                    ->label('Kecamatan'),
                Tables\Filters\SelectFilter::make('village_id')
                    ->relationship('village', 'name')
                    ->label('Kelurahan / Desa')
                    ->searchable(),
            ]);
    }

    public static function getListTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
            Tables\Columns\TextColumn::make('contractor.name')->label('Kontraktor')->sortable(),
            Tables\Columns\TextColumn::make('consultant.name')->label('Konsultan')->sortable(),
            Tables\Columns\TextColumn::make('district.name')->label('Kecamatan')->sortable(),
            Tables\Columns\TextColumn::make('village.name')->label('Kelurahan / Desa')->sortable(),
            Tables\Columns\TextColumn::make('length')->label('Panjang')->sortable(),
            Tables\Columns\TextColumn::make('width')->label('Lebar')->sortable(),
            ProgressColumn::make('highest_progress')
                ->label('Realisasi Fisik Terakhir')
                ->state(function ($record) {

                    $real1 = floatval($record->real_1);
                    $real2 = floatval($record->real_2);
                    $real3 = floatval($record->real_3);
                    $real4 = floatval($record->real_4);
                    $real5 = floatval($record->real_5);
                    $real6 = floatval($record->real_6);
                    $real7 = floatval($record->real_7);
                    $real8 = floatval($record->real_8);
                    
                    $values = [$real1, $real2, $real3, $real4, $real5, $real6, $real7, $real8];
                    $maxValue = max($values);
                    
                    return number_format($maxValue, 2);
                }),
            Tables\Columns\TextColumn::make('team')->label('Tim PHO')->searchable(),
            Tables\Columns\TextColumn::make('construct_type')->label('Jenis Konstruksi')->searchable(),
            Tables\Columns\TextColumn::make('spending_type')->label('Jenis Pembayaran')->searchable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),

        ];
    }

    public static function getGridTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
            Tables\Columns\TextColumn::make('contractor.name')->label('Kontraktor')->sortable(),
            Tables\Columns\TextColumn::make('consultant.name')->label('Konsultan')->sortable(),
            Tables\Columns\TextColumn::make('district.name')->label('Kecamatan')->sortable(),
            Tables\Columns\TextColumn::make('village.name')->label('Kelurahan / Desa')->sortable(),
            Tables\Columns\TextColumn::make('length')->label('Panjang')->sortable(),
            Tables\Columns\TextColumn::make('width')->label('Lebar')->sortable(),
            ProgressColumn::make('highest_progress')
                ->label('Realisasi Fisik Terakhir')
                ->state(function ($record) {

                    $real1 = floatval($record->real_1);
                    $real2 = floatval($record->real_2);
                    $real3 = floatval($record->real_3);
                    $real4 = floatval($record->real_4);
                    $real5 = floatval($record->real_5);
                    $real6 = floatval($record->real_6);
                    $real7 = floatval($record->real_7);
                    $real8 = floatval($record->real_8);
                    
                    $values = [$real1, $real2, $real3, $real4, $real5, $real6, $real7, $real8];
                    $maxValue = max($values);
                    
                    return number_format($maxValue, 2);
                }),
            Tables\Columns\TextColumn::make('team')->label('Tim PHO')->searchable(),
            Tables\Columns\TextColumn::make('construct_type')->label('Jenis Konstruksi')->searchable(),
            Tables\Columns\TextColumn::make('spending_type')->label('Jenis Pembayaran')->searchable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),

        ];
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
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}

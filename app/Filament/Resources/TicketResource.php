<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\District;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\TicketStatus;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TicketResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketResource\RelationManagers;
use Filament\Forms\Components\Html;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Manajemen Usulan / Pengaduan';

    protected static ?string $label = 'Usulan / Pengaduan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->label('Jenis Pengaduan/Usulan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\MarkDownEditor::make('issue')
                    ->label('Permasalahan')
                    ->required()
                    ->columnSpanFull(),
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
                Forms\Components\FileUpload::make('photo_url')
                    ->label('Foto/Gambar Bukti Dukung')
                    ->image()
                    ->directory('aduan')
                    ->columnSpanFull(),
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
                    ->label('Lokasi yang Dilaporkan')
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
                Forms\Components\Select::make('status')
                    ->enum(TicketStatus::class)
                    ->options(TicketStatus::class)
                    ->required()
                    ->default('open'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->selectable()            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('ID Ticket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Pengaduan/Usulan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('village.name')
                    ->label('Kelurahan / Desa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo_url')
                    ->label('Foto/Gambar Bukti Dukung')
                    ->size(150, 100),
                Tables\Columns\TextColumn::make('lat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lng')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
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
            'index' => Pages\ManageTickets::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}

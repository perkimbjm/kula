<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\TicketStatus;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TicketResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketResource\RelationManagers;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Manajemen Usulan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('issue')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('district_id')
                    ->relationship('district', 'name')
                    ->required(),
                Forms\Components\Select::make('village_id')
                    ->relationship('village', 'name')
                    ->required(),
                Forms\Components\FileUpload::make('photo_url')
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
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('village.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo_url'),
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
                Tables\Actions\DeleteAction::make(),
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
}

<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TicketFeedback;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketFeedbackResource\Pages;
use App\Filament\Resources\TicketFeedbackResource\RelationManagers;
use IbrahimBougaoua\FilamentRatingStar\Columns\Components\RatingStar;
use IbrahimBougaoua\FilamentRatingStar\Forms\Components\RatingStar as RatingStarForm;

class TicketFeedbackResource extends Resource
{
    protected static ?string $model = TicketFeedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static ?string $navigationGroup = 'Manajemen Usulan / Pengaduan';

    protected static ?string $label = 'Tanggapan dari Respon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ticket_id')
                    ->label('Nomor Tiket')
                    ->options(function (callable $get, $record) {
                        $query = Ticket::query();
                        
                        if ($record) {
                            $query->where(function ($q) use ($record) {
                                $q->whereDoesntHave('feedback')
                                  ->orWhere('id', $record->ticket_id);
                            });
                        } else {
                            $query->whereDoesntHave('feedback');
                        }
                        
                        return $query->get()
                            ->pluck('ticket_number', 'id')
                            ->toArray();
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $ticket = Ticket::with('ticketResponses')->find($state);
                            if ($ticket) {
                                $set('issue', $ticket->issue);
                                $latestResponse = $ticket->ticketResponses()->latest()->first();
                                $set('response', $latestResponse ? $latestResponse->response : '');
                            } else {
                                $set('issue', '');
                                $set('response', '');
                            }
                        }
                    })
                    ->afterStateHydrated(function ($state, callable $set) {
                        if ($state) {
                            $ticket = Ticket::with('ticketResponses')->find($state);
                            if ($ticket) {
                                $set('issue', $ticket->issue);
                                $latestResponse = $ticket->ticketResponses()->latest()->first();
                                $set('response', $latestResponse ? $latestResponse->response : '');
                            }
                        }
                    }),
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required(),
                Forms\Components\TextInput::make('issue')
                    ->label('Masalah')
                    ->readonly(),
                Forms\Components\TextInput::make('response')
                    ->label('Tanggapan')
                    ->readonly(),
                Forms\Components\Textarea::make('feedback')
                    ->required()
                    ->columnSpanFull(),
                RatingStarForm::make('rating')
                    ->label('Rating'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
                return static::getModel()::query()
                    ->with(['ticket', 'user']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('ticket.ticket_number')
                    ->label('Nomor Tiket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticket.latestResponse')
                    ->label('Tanggapan Terakhir'),
                Tables\Columns\TextColumn::make('feedback')
                    ->label('Feedback')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                RatingStar::make('rating')
                    ->size('sm'),
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
            'index' => Pages\ManageTicketFeedback::route('/'),
        ];
    }
}
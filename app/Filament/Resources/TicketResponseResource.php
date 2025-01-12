<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TicketResponse;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\TicketResponseResource\Pages;
use App\Filament\Resources\TicketResponseResource\RelationManagers;

class TicketResponseResource extends Resource
{
    protected static ?string $model = TicketResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Manajemen Usulan / Pengaduan';

    protected static ?string $label = 'Respon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ticket_id')
                    ->label('Nomor Tiket')
                    ->options(function (callable $get, $record) {
                        $query = Ticket::query();
                        
                        if ($record) {
                            // Jika mode edit, include tiket yang sedang diedit
                            $query->where(function ($q) use ($record) {
                                $q->whereDoesntHave('ticketResponses')
                                  ->orWhere('id', $record->ticket_id);
                            });
                        } else {
                            // Jika mode create, hanya tampilkan tiket tanpa response
                            $query->whereDoesntHave('ticketResponses');
                        }
                        
                        return $query->get()
                            ->pluck('ticket_number', 'id')
                            ->toArray();
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $ticket = Ticket::with('user')->find($state);
                            if ($ticket) {
                                $set('issue', $ticket->issue);
                                $set('user_id', $ticket->user_id);
                                $set('user_name', $ticket->user->name);
                            } else {
                                $set('issue', '');
                                $set('user_id', '');
                                $set('user_name', '');
                            }
                        }
                    })
                    ->afterStateHydrated(function ($state, callable $set) {
                        // Jika state sudah ada pada mode edit, pastikan kita set issue dan user_name
                        if ($state) {
                            $ticket = Ticket::with('user')->find($state);
                            if ($ticket) {
                                $set('issue', $ticket->issue);
                                $set('user_id', $ticket->user_id);
                                $set('user_name', $ticket->user->name);
                            }
                        }
                    }),

                Forms\Components\Hidden::make('admin_id')
                    ->default(Auth::id())
                    ->required(),

                Forms\Components\TextInput::make('user_name')
                    ->label('User yang Mengadu')
                    ->required()
                    ->readOnly()
                    ->dehydrated(false),

                Forms\Components\Hidden::make('user_id')
                    ->required(),

                Forms\Components\Textarea::make('issue')
                    ->label('Permasalahan')
                    ->required()
                    ->columnSpanFull()
                    ->readOnly(),

                Forms\Components\Textarea::make('response')
                    ->label('Tanggapan')
                    ->required()
                    ->columnSpanFull(),
                ]);
            }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket.ticket_number')
                    ->label('Nomor Tiket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticket.issue')
                    ->label('Permasalahan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('response')
                    ->label('Respon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Admin Pemberi Respon')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User yang Mengadu')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTicketResponses::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsolSpvDetailResource\Pages;
use App\Filament\Resources\ConsolSpvDetailResource\RelationManagers;
use App\Models\ConsolSpvDetail;
use App\Models\Work;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Imports\ConsolSpvDetailImporter;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ConsolSpvDetailResource extends Resource
{
    protected static ?string $model = ConsolSpvDetail::class;

    protected static ?string $navigationIcon = 'heroicon-s-folder-plus';

    protected static ?string $navigationGroup = 'Manajemen PBJ';

    protected static ?string $label = 'Detail Konsolidasi Paket Pengawasan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('work_name')
                    ->label('Nama Paket (dari Works)')
                    ->options(Work::pluck('name', 'name'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $set('name', $state)),
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ee')
                    ->label('HPS')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('Rp')
                    ->rules(['regex:/^\d+(\.\d{1,2})?$/'])
                    ->validationMessages([
                        'required' => 'HPS wajib diisi.',
                        'min' => 'HPS tidak boleh negatif.',
                        'regex' => 'HPS maksimal dua angka di belakang koma.'
                    ]),
                Forms\Components\TextInput::make('nego_value')
                    ->label('Harga Negosiasi')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('Rp')
                    ->validationMessages([
                        'required' => 'Harga Negosiasi wajib diisi.',
                        'min' => 'Harga Negosiasi tidak boleh negatif.'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ee')
                    ->label('HPS')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->label('Harga Negosiasi')
                    ->numeric(2)
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
            ->headerActions([
                \Filament\Tables\Actions\Action::make('import')
                    ->label('Import Data')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->color('warning')
                    ->form([
                        \Filament\Forms\Components\FileUpload::make('file')
                            ->label('File XLSX')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '.xlsx'])
                            ->maxSize(5120)
                            ->required()
                            ->disk('local')
                            ->directory('temp-imports')
                            ->visibility('private')
                            ->helperText('Format file harus .xlsx dengan ukuran maksimal 5MB'),
                    ])
                    ->action(function (array $data) {
                        try {
                            if (isset($data['file'])) {
                                $filePath = storage_path('app/' . $data['file']);
                                if (!file_exists($filePath)) {
                                    throw new \Exception('File tidak ditemukan di: ' . $filePath);
                                }
                                if (!is_readable($filePath)) {
                                    throw new \Exception('File tidak dapat dibaca di: ' . $filePath);
                                }
                                Excel::import(new ConsolSpvDetailImporter, $filePath);
                                Notification::make()
                                    ->success()
                                    ->title('Data berhasil diimport!')
                                    ->send();
                            } else {
                                throw new \Exception('File tidak ditemukan dalam request');
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Gagal import data')
                                ->body($e->getMessage())
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Import Data Detail Konsolidasi Pengawasan')
                    ->modalDescription('Pastikan format file sesuai dengan template yang telah ditentukan. Harap gunakan template import terlebih dahulu supaya berhasil.')
                    ->modalSubmitActionLabel('Upload dan Import'),
                \Filament\Tables\Actions\Action::make('download-template')
                    ->label('Download Template')
                    ->icon('heroicon-o-cloud-arrow-down')
                    ->color('danger')
                    ->action(function () {
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setCellValue('A1', 'no');
                        $sheet->setCellValue('B1', 'nama_paket');
                        $sheet->setCellValue('C1', 'hps');
                        $sheet->setCellValue('D1', 'harga_negosiasi');
                        $writer = new Xlsx($spreadsheet);
                        $filename = 'template_consol_spv_detail.xlsx';
                        $tempPath = storage_path('app/' . $filename);
                        $writer->save($tempPath);
                        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
                    }),
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
            'index' => Pages\ListConsolSpvDetails::route('/'),
            'create' => Pages\CreateConsolSpvDetail::route('/create'),
            'edit' => Pages\EditConsolSpvDetail::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsolPlanDetailResource\Pages;
use App\Filament\Resources\ConsolPlanDetailResource\RelationManagers;
use App\Models\ConsolPlanDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Imports\ConsolPlanDetailImporter;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ConsolPlanDetailResource extends Resource
{
    protected static ?string $model = ConsolPlanDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-plus';

    protected static ?string $navigationGroup = 'Manajemen PBJ';

    protected static ?string $label = 'Detail Konsolidasi Paket Perencanaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ee')
                    ->label('Nilai HPS')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->rules(['regex:/^\d+(\.\d{1,2})?$/'])
                    ->prefix('Rp')
                    ->validationMessages([
                        'required' => 'Nilai HPS wajib diisi.',
                        'min' => 'Nilai HPS tidak boleh negatif.',
                        'regex' => 'Nilai HPS maksimal dua angka di belakang koma.'
                    ]),
                Forms\Components\TextInput::make('nego_value')
                    ->label('Penawaran')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('Rp')
                    ->validationMessages([
                        'required' => 'Nilai Penawaran wajib diisi.',
                        'min' => 'Nilai Penawaran tidak boleh negatif.'
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->selectable()
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ee')
                    ->label('Nilai HPS')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->label('Penawaran')
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
                                \Maatwebsite\Excel\Facades\Excel::import(new \App\Filament\Imports\ConsolPlanDetailImporter(), $filePath);
                                \Filament\Notifications\Notification::make()
                                    ->success()
                                    ->title('Data berhasil diimport!')
                                    ->send();
                            } else {
                                throw new \Exception('File tidak ditemukan dalam request');
                            }
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Gagal import data')
                                ->body($e->getMessage())
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Import Data Detail Konsolidasi')
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
                        $sheet->setCellValue('B1', 'Nama Paket');
                        $sheet->setCellValue('C1', 'HPS');
                        $sheet->setCellValue('D1', 'Harga Negosiasi');
                        $writer = new Xlsx($spreadsheet);
                        $filename = 'template_consol_plan_detail.xlsx';
                        $tempPath = storage_path('app/' . $filename);
                        $writer->save($tempPath);
                        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
                    }),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsolPlanDetails::route('/'),
            'create' => Pages\CreateConsolPlanDetail::route('/create'),
            'edit' => Pages\EditConsolPlanDetail::route('/{record}/edit'),
        ];
    }
}

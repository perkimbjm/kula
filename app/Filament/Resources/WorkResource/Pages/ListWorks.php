<?php

namespace App\Filament\Resources\WorkResource\Pages;

use App\Models\Work;
use Filament\Actions;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Imports\WorkImporter;
use App\Filament\Resources\WorkResource;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Exports\FilteredWorkExport;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Http\UploadedFile;


class ListWorks extends ListRecords
{
    protected static string $resource = WorkResource::class;

    protected function getHeaderActions(): array
    {

        return [
            Actions\CreateAction::make(),
            Actions\Action::make('export')
                ->label('Ekspor ke Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->form([
                    CheckboxList::make('selectedColumns')
                        ->label('Pilih Kolom yang ingin diekspor')
                        ->options(function () {
                            return collect(FilteredWorkExport::getColumns())->mapWithKeys(function ($column) {
                                return [$column->getName() => $column->getLabel()];
                            })->toArray();
                        })
                        ->default(function () {
                            return collect(FilteredWorkExport::getColumns())
                                ->map(fn ($column) => $column->getName())
                                ->toArray();
                        })
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filters = $this->getTableFilters();
                        $selectedColumns = $data['selectedColumns'];

                        return Excel::download(
                            new FilteredWorkExport($filters, $selectedColumns),
                            'laporan_kemajuan_pekerjaan_' . now()->format('Y-m-d_His') . '.xlsx'
                        );
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal mengeksport data')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            Actions\Action::make('import')
                ->label('Import Data Excel')
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('warning')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih File')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            '.xlsx',
                            '.xls'
                        ])
                        ->maxSize(5120)
                        ->required()
                        ->disk('local') // Tambahkan ini
                        ->directory('temp-imports') // Tambahkan ini
                        ->visibility('private') // Tambahkan ini
                        ->helperText('Format file harus .xlsx dengan ukuran maksimal 5MB')
                ])
                ->action(function (array $data) {
                    try {
                        if (isset($data['file'])) {
                            $filePath = storage_path('app/' . $data['file']);

                            // Debug information
                            \Log::info('File path:', [
                                'storage_path' => $filePath,
                                'exists' => file_exists($filePath),
                                'readable' => is_readable($filePath),
                                'file_size' => filesize($filePath), // Log file size
                                'mime_type' => mime_content_type($filePath), // Log mime type
                            ]);

                            if (!file_exists($filePath)) {
                                throw new \Exception('File tidak ditemukan di: ' . $filePath);
                            }

                            if (!is_readable($filePath)) {
                                throw new \Exception('File tidak dapat dibaca di: ' . $filePath);
                            }

                            // Assuming $filePath is a valid file upload path
                            $uploadedFile = new UploadedFile($filePath, basename($filePath));

                            // Log before importing the file
                            \Log::info('Starting import process for file:', ['file_path' => $filePath]);

                            // Create an Import object with the file
                            $import = new Import();

                            // Pass the Import object to the WorkImporter
                            $importResult =  Excel::import(new WorkImporter($import, WorkImporter::getColumns(), []), $filePath);

                            // Log import result (if applicable)
                            \Log::info('Import result:', ['import_result' => $importResult]);


                            // Notification for success
                            Notification::make()
                                ->success()
                                ->title('Data berhasil diimport!')
                                ->send();
                        } else {
                            throw new \Exception('File tidak ditemukan dalam request');
                        }
                    } catch (\Exception $e) {
                        // Log the error message and stack trace
                        \Log::error('Import error: ' . $e->getMessage());
                        \Log::error('Stack trace: ' . $e->getTraceAsString());

                        Notification::make()
                            ->danger()
                            ->title('Gagal import data')
                            ->body($e->getMessage())
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Import Data Proyek')
                ->modalDescription('Pastikan format file sesuai dengan template yang telah ditentukan. Harap Gunakan template import terlebih dahulu supaya berhasil')
                ->modalSubmitActionLabel('Upload dan Import'),

                Actions\Action::make('downloadTemplate')
                    ->label('Download Template')
                    ->icon('heroicon-o-cloud-arrow-down')
                    ->color('danger')
                    ->url('/data/template_data_progres.xlsx')
                    ->openUrlInNewTab(true),

        ];
    }
}

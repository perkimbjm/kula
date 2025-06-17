<?php

namespace App\Filament\Resources\WorkResource\Pages;

use App\Models\Work;
use Filament\Actions;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Imports\WorkImporter;
use App\Filament\Exports\WorkExporter;
use App\Filament\Resources\WorkResource;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class ListWorks extends ListRecords
{
    protected static string $resource = WorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('clearCache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    // Clear cache untuk performa yang lebih baik
                    Cache::forget('work_years');
                    Cache::forget('work_account_codes');
                    Cache::forget('work_programs');
                    Cache::flush(); // Clear all work scoreboard cache

                    Notification::make()
                        ->success()
                        ->title('Cache berhasil dibersihkan!')
                        ->body('Data akan dimuat ulang pada akses berikutnya.')
                        ->send();
                })
                ->requiresConfirmation(),
            Actions\Action::make('export')
                ->label('Ekspor ke Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->form([
                    CheckboxList::make('selectedColumns')
                        ->label('Pilih Kolom yang ingin diekspor')
                        ->options(WorkExporter::getColumns())
                        ->default(array_keys(WorkExporter::getColumns()))
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filters = $this->getTableFilters();
                        $selectedColumns = $data['selectedColumns'];

                        return Excel::download(
                            new WorkExporter($filters, $selectedColumns),
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
                        ->disk('local')
                        ->directory('temp-imports')
                        ->visibility('private')
                        ->helperText('Format file harus .xlsx dengan ukuran maksimal 5MB')
                ])
                ->action(function (array $data) {
                    try {
                        if (isset($data['file'])) {
                            $filePath = storage_path('app/' . $data['file']);

                            // Debug information
                            Log::info('Work file path:', [
                                'storage_path' => $filePath,
                                'exists' => file_exists($filePath),
                                'readable' => is_readable($filePath),
                                'file_size' => file_exists($filePath) ? filesize($filePath) : 0,
                                'mime_type' => file_exists($filePath) ? mime_content_type($filePath) : 'unknown',
                            ]);

                            if (!file_exists($filePath)) {
                                throw new \Exception('File tidak ditemukan di: ' . $filePath);
                            }

                            if (!is_readable($filePath)) {
                                throw new \Exception('File tidak dapat dibaca di: ' . $filePath);
                            }

                            // Log before importing the file
                            Log::info('Starting work import process for file:', ['file_path' => $filePath]);

                            // Import the file
                            Excel::import(new WorkImporter(), $filePath);

                            // Notification for success
                            Notification::make()
                                ->success()
                                ->title('Data kemajuan pekerjaan berhasil diimport!')
                                ->send();
                        } else {
                            throw new \Exception('File tidak ditemukan dalam request');
                        }
                    } catch (\Exception $e) {
                        // Log the error message and stack trace
                        Log::error('Work import error: ' . $e->getMessage());
                        Log::error('Stack trace: ' . $e->getTraceAsString());

                        Notification::make()
                            ->danger()
                            ->title('Gagal import data')
                            ->body($e->getMessage())
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Import Data Kemajuan Pekerjaan Fisik')
                ->modalDescription('Pastikan format file sesuai dengan template yang telah ditentukan. Harap gunakan template import terlebih dahulu supaya berhasil.')
                ->modalSubmitActionLabel('Upload dan Import'),

            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('danger')
                ->url('/data/template_work_new.xlsx')
                ->openUrlInNewTab(true),
        ];
    }

    /**
     * Optimize table query untuk performa yang lebih baik
     */
    protected function modifyQuery(Builder $query): Builder
    {
        return $query->withFullRelations()
            ->when(
                request()->has('tableSearch'),
                fn ($q) => $q->where(function ($searchQuery) {
                    $search = request('tableSearch');
                    $searchQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('contract_number', 'like', "%{$search}%")
                        ->orWhereHas('district', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('village', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('contractor', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                })
            );
    }
}

<?php

namespace App\Filament\Imports;

use App\Models\Work;
use Illuminate\Support\Facades\DB;
use Filament\Actions\Imports\Importer;
use Maatwebsite\Excel\Concerns\ToModel;
use Filament\Actions\Imports\ImportColumn;
use Maatwebsite\Excel\Events\BeforeImport;
use Filament\Actions\Imports\Models\Import;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class WorkImporter extends Importer implements WithHeadingRow, withStartRow, ToModel,
WithBatchInserts
{
    protected static ?string $model = Work::class;

    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris ke-2 (setelah header)
    }
    

    protected $columnMapping = [
        'Tahun' => 'year',
        'Nama Paket' => 'name',
        'Tanggal Kontrak' => 'contract_date',
        'Nomor Kontrak' => 'contract_number',
        'ID Kontraktor' => 'contractor',
        'ID Konsultan Perencana' => 'consultant',
        'ID Konsultan Pengawas' => 'supervisor',
        'Nilai Kontrak' => 'contract_value',
        'Kemajuan Pelaksanaan Fisik' => 'progress',
        'Tanggal Cut Off / Selesai' => 'cutoff',
        'Status Pelaksanaan' => 'status',
        'Jumlah Terbayar' => 'paid',
    ];

    public function model(array $row)
    {
        $mappedRow = [];
        foreach ($this->columnMapping as $excelColumn => $dbColumn) {
            $cleanedColumn = trim($excelColumn);
            $mappedRow[$dbColumn] = $row[$cleanedColumn] ?? null;
        }
        
        \Log::info('Processing row:', $mappedRow);

       

        try {
            return new Work([
                'year' => $mappedRow['year'],
                'name' => $mappedRow['name'],
                'contract_date' => is_numeric($mappedRow['contract_date']) ? \Carbon\Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($mappedRow['contract_date'])->format('Y-m-d')) : (!empty($mappedRow['contract_date']) ? \Carbon\Carbon::parse($mappedRow['contract_date']) : null),
                'contract_number' => $mappedRow['contract_number'] ?? null,
                'contractor_id' => !empty($mappedRow['contractor']) ? DB::table('contractors')->where('id', $mappedRow['contractor'])->value('id') : null,
                'consultant_id' => !empty($mappedRow['consultant']) ? DB::table('consultants')->where('id', $mappedRow['consultant'])->value('id') : null,
                'supervisor_id' => !empty($mappedRow['supervisor']) ? DB::table('consultants')->where('id', $mappedRow['supervisor'])->value('id') : null,
                'contract_value' => $mappedRow['contract_value'] ?? null,
                'progress' => $mappedRow['progress'] ?? null,
                'cutoff' => is_numeric($mappedRow['cutoff']) ? \Carbon\Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($mappedRow['cutoff'])->format('Y-m-d')) : (!empty($mappedRow['cutoff']) ? \Carbon\Carbon::parse($mappedRow['cutoff']) : null),
                'status' => $mappedRow['status'],
                'paid' => $mappedRow['paid'] ?? null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error processing row:', [
                'row' => $mappedRow,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('year')
                ->label('Tahun')
                ->requiredMapping()
                ->numeric(),
            ImportColumn::make('name')
                ->label('Nama Paket')
                ->requiredMapping(),
            ImportColumn::make('contract_date')
                ->label('Tanggal Kontrak')
                ->rules(['nullable']),
            ImportColumn::make('contract_number')
                ->label('Nomor Kontrak'),
            ImportColumn::make('contractor')
                ->label('ID Kontraktor')
                ->relationship()
                ->rules(['nullable']),
            ImportColumn::make('consultant')
                ->label('ID Konsultan Perencana')
                ->relationship()
                ->rules(['nullable']),
            ImportColumn::make('supervisor')
                ->label('ID Konsultan Pengawas')
                ->relationship()
                ->rules(['nullable']),
            ImportColumn::make('contract_value')
                ->label('Nilai Kontrak')
                ->numeric(),
            ImportColumn::make('progress')
                ->label('Kemajuan Pelaksanaan Fisik')
                ->numeric(),
            ImportColumn::make('cutoff')
                ->label('Tanggal Cut Off / Selesai')
                ->rules(['nullable']),
            ImportColumn::make('status')
                ->label('Status Pelaksanaan')
                ->requiredMapping(),
            ImportColumn::make('paid')
                ->label('Jumlah Terbayar')
                ->numeric(),
        ];
    }


    public function resolveRecord(): ?Work
    {
        try {
            DB::beginTransaction();

            \Log::info('Raw data received:', $this->data);
            
            // Validasi data yang diterima
            if (empty($this->data)) {
                \Log::warning('No data received in resolveRecord');
                return null;
            }

            // Map the Excel columns to database fields
            $work = new Work([
                'year' => $this->data['year'] ?? null,
                'name' => $this->data['name'] ?? null,
                'contract_date' => !empty($this->data['contract_date']) ? \Carbon\Carbon::parse($this->data['contract_date']) : null,
                'contract_number' => $this->data['contract_number'] ?? null,
                'contractor' => $this->data['contractor'] ?? null,
                'consultant' => $this->data['consultant'] ?? null,
                'supervisor' => $this->data['supervisor'] ?? null,
                'contract_value' => $this->data['contract_value'] ?? null,
                'progress' => $this->data['progress'] ?? null,
                'cutoff' => !empty($this->data['cutoff']) ? \Carbon\Carbon::parse($this->data['cutoff']) : null,
                'status' => $this->data['status'] ?? null,
                'paid' => $this->data['paid'] ?? null,
            ]);

            \Log::info('Mapped work data:', $work->toArray());
            
            $work->save();
            
            DB::commit();
            
            return $work;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in resolveRecord: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        return "Successfully imported {$import->successful_rows} works.";
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                \Log::info('Excel headers:', $event->getDelegate()->getActiveSheet()->toArray()[0]);
            },
        ];
    }
}
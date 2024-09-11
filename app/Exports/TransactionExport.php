<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting, WithCustomStartCell
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $vehicles;
    protected $totalPendapatan = 0;
    protected $type;

    public function __construct($startDate, $endDate, $vehicles, $type)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->vehicles = is_array($vehicles) ? $vehicles : explode(',', $vehicles);
        $this->type = $type;
    }

    public function query()
    {
        $query = Transaction::query();

        if ($this->type === 'rekap') {
            $query->select(DB::raw('EXTRACT(MONTH FROM exit_time) as bulan, EXTRACT(YEAR FROM exit_time) as tahun, COUNT(*) as total_parkir, SUM(total_cost) as total_cost, SUM(payment) as total_bayar, SUM(change_pay) as total_kembalian, SUM(payment - change_pay) as total_pendapatan'))
                ->whereBetween('exit_time', ["{$this->startDate} 00:00:00", "{$this->endDate} 23:59:59"])
                ->where('status', '=', 'COMPLETE')
                ->groupBy(DB::raw('EXTRACT(MONTH FROM exit_time), EXTRACT(YEAR FROM exit_time)'))
                ->orderByRaw('EXTRACT(YEAR FROM exit_time), EXTRACT(MONTH FROM exit_time)');
        } else {
            $query->whereBetween('exit_time', ["{$this->startDate} 00:00:00", "{$this->endDate} 23:59:59"])
                ->where('status', '=', 'COMPLETE')
                ->with(['parkingArea', 'parkingRate.vehicle', 'user']);
        }

        if ($this->vehicles[0] != '0') {
            $query->whereHas('parkingRate.vehicle', function ($subQuery) {
                $subQuery->whereIn('id', $this->vehicles);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        if ($this->type === 'rekap') {
            return [
                'Bulan',
                'Tahun',
                'Total Parkir',
                'Total Cost',
                'Total Bayar',
                'Total Kembalian',
                'Total Pendapatan'
            ];
        } else {
            return [
                'No. Tiket',
                'Plat Motor',
                'Tipe Kendaraan',
                'Masuk Parkir',
                'Keluar Parkir',
                'Durasi',
                'Area Parkir',
                'Total Cost',
                'Bayar',
                'Kembalian',
                'Pendapatan'
            ];
        }
    }

    public function map($transaction): array
    {
        if ($this->type === 'rekap') {
            $months = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            return [
                $months[$transaction->bulan],
                $transaction->tahun,
                $transaction->total_parkir,
                number_format($transaction->total_cost, 0, '', ','),
                number_format($transaction->total_bayar, 0, '', ','),
                number_format($transaction->total_kembalian, 0, '', ','),
                number_format($transaction->total_pendapatan, 0, '', ',')
            ];
        } else {
            $pendapatan = $transaction->payment - $transaction->change_pay;
            $this->totalPendapatan += $pendapatan;

            return [
                $transaction->no_ticket,
                $transaction->license_plate,
                $transaction->parkingRate->vehicle->name ?? '',
                $transaction->entry_time,
                $transaction->exit_time,
                $transaction->duration,
                $transaction->parkingArea->name ?? '',
                number_format($transaction->total_cost, 0, '', ','),
                number_format($transaction->payment, 0, '', ','),
                number_format($transaction->change_pay, 0, '', ','),
                number_format($pendapatan, 0, '', ',')
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        $lastDataRow = $sheet->getHighestRow();
        $totalRow = $lastDataRow + 1;

        $formattedTotalPendapatan = number_format($this->totalPendapatan, 0, '', ',');

        if ($this->type === 'rekap') {
            return [
                1 => ['font' => ['bold' => true]],
                'A1:G1' => ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'DDDDDD']]]
            ];
        } else {
            $sheet->setCellValue("J{$totalRow}", 'Total Pendapatan:');
            $sheet->setCellValue("K{$totalRow}", $formattedTotalPendapatan);

            return [
                1 => ['font' => ['bold' => true]],
                'A1:K1' => ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'DDDDDD']]],
                $totalRow => ['font' => ['bold' => true]],
                "J{$totalRow}:K{$totalRow}" => ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFFF00']]],
            ];
        }
    }

    public function columnFormats(): array
    {
        if ($this->type === 'rekap') {
            return [
                'D' => NumberFormat::FORMAT_NUMBER,
                'E' => NumberFormat::FORMAT_NUMBER,
                'F' => NumberFormat::FORMAT_NUMBER,
                'G' => NumberFormat::FORMAT_NUMBER,
            ];
        } else {
            return [
                'H' => NumberFormat::FORMAT_NUMBER,
                'I' => NumberFormat::FORMAT_NUMBER,
                'J' => NumberFormat::FORMAT_NUMBER,
                'K' => NumberFormat::FORMAT_NUMBER,
            ];
        }
    }

    public function startCell(): string
    {
        return 'A1';
    }
}

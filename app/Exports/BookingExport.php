<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'invoice_no' => $item->invoice_no,
                'name' => $item->user->name,
                'email' => $item->user->email,
                'tanggal_pesan' => $item->created_at->format('d M Y'),
                'jumlah_orang' => $item->total_person,
                'total_bayar' => $item->paid_amount,
                'metode' => $item->payment_method,
                'status' => $item->payment_status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No Faktur',
            'Nama Pengguna',
            'Email',
            'Tanggal Pemesanan',
            'Jumlah Orang',
            'Total Bayar',
            'Metode',
            'Status Pembayaran',
        ];
    }
}


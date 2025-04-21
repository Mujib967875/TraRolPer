<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemesanan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        table, th, td { border: 1px solid black; padding: 5px; text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 class="text-center">Laporan Booking</h2>
    @if($start && $end)
        <p>Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
    @else
        <p>Periode: Hari ini ({{ \Carbon\Carbon::today()->format('d M Y') }})</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Faktur</th>
                <th>Nama Pengguna</th>
                <th>Tanggal Pemesanan</th>
                <th>Jumlah Orang</th>
                <th>Total Bayar</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $key => $b)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $b->invoice_no }}</td>
                <td>{{ $b->user->name }}</td>
                <td>{{ $b->created_at->format('d M Y') }}</td>
                <td>{{ $b->total_person }}</td>
                <td>Rp. {{ number_format($b->paid_amount, 0, ',', '.') }}</td>
                <td>{{ $b->payment_method }}</td>
                <td>
                    @if ($b->payment_method == 'Midtrans')
                    <p> Non-tunai </p>
                    @elseif ($b->payment_method == 'Cash')
                    <p> Cash </p>
                    @elseif ($b->payment_method == 'Stripe')
                    <p> Stripe </p>
                    @elseif ($b->payment_method == 'PayPal')
                    <p> Paypal </p>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Pemasukan:</td>
                <td colspan="3">Rp. {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>

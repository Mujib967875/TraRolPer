<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\BookingExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminBookingController extends Controller
{
    public function index(){
        $year = request()->get('year', date('Y'));

        $monthlyIncome = Booking::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(paid_amount) as total_income')
            )
            ->whereYear('created_at', $year)
            ->where('payment_status', 'Completed')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_income', 'month');

        $today = Carbon::today();

        $bookings = Booking::with('user')
            ->whereDate('created_at', $today)
            ->where('payment_status', 'Completed')
            ->get();

        return view('admin.booking.index', compact('bookings','monthlyIncome','year'));

    }

    public function filter(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $bookings = Booking::with('user')
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [
                    Carbon::parse($start)->startOfDay(),
                    Carbon::parse($end)->endOfDay()
                ]);
            })
            ->where('payment_status', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.booking.index', compact('bookings'));
    }

    public function exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $bookings = Booking::with('user')
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [
                    Carbon::parse($start)->startOfDay(),
                    Carbon::parse($end)->endOfDay()
                ]);
            })
            ->where('payment_status', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $bookings->sum('paid_amount');

        $pdf = Pdf::loadView('admin.dashboard.report_pdf', compact('bookings', 'total', 'start', 'end'))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pemesanan.pdf');
    }

    public function exportExcel(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $bookings = Booking::with('user')
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [
                    Carbon::parse($start)->startOfDay(),
                    Carbon::parse($end)->endOfDay()
                ]);
            })
            ->where('payment_status', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return Excel::download(new BookingExport($bookings), 'laporan-pemesanan.xlsx');
    }

}

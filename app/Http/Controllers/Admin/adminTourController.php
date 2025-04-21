<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Tour;
use Spatie\Activitylog\Models\Activity;

class adminTourController extends Controller
{
    public function index()
    {
        $trashCount = Tour::onlyTrashed()->count();

        $tours = Tour::with('package')->get();
        return view('admin.tour.index', compact('tours', 'trashCount'));
    }

    public function create()
    {
        $packages = Package::orderBy('name','asc')->get();
        return view('admin.tour.create',compact('packages'));
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'tour_start_date' =>'required',
            'tour_end_date' =>'required',
            'booking_end_date' =>'required',
            'total_seat' =>'required'
        ]);

        $tour = new Tour();
        $tour->package_id = $request->package_id;
        $tour->tour_start_date = $request->tour_start_date;
        $tour->tour_end_date = $request->tour_end_date;
        $tour->booking_end_date = $request->booking_end_date;
        $tour->total_seat = $request->total_seat;
        $tour->save();

        // Ambil data baru
        $newData = $tour->getAttributes();

        // Format log
        $logData = [
            'attributes' => $newData,
            'old' => 'Null' ,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'created',
            'subject_type' => 'App\Models\Tour',
            'subject_id' => $tour->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Tour untuk Package ' .$tour->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_tour_index')->with('success', 'Tour berhasil ditambahkan');
    }

    public function edit($id)
    {
        $packages = Package::orderBy('name','asc')->get();
        $tour = Tour::where('id', $id)->first();
        return view('admin.tour.edit', compact('tour','packages'));
    }

    public function edit_submit(Request $request, $id)
    {

        $tours = Tour::findOrFail($id);

        // Simpan data lama
        $oldData = $tours->getAttributes();

        $tour = Tour::where('id', $id)->first();

        $request->validate([
            'tour_start_date' =>'required',
            'tour_end_date' =>'required',
            'booking_end_date' =>'required',
            'total_seat' =>'required'
        ]);

        $tour->package_id = $request->package_id;
        $tour->tour_start_date = $request->tour_start_date;
        $tour->tour_end_date = $request->tour_end_date;
        $tour->booking_end_date = $request->booking_end_date;
        $tour->total_seat = $request->total_seat;
        $tour->update();

        // Ambil data baru
        $newData = $tour->getAttributes();

        // Format log
        $logData = [
            'attributes' => $newData,
            'old' => $oldData ,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'updated',
            'subject_type' => 'App\Models\Tour',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Tour di Package ' .$tour->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_tour_index')->with('success', 'Tour berhasil di perbarui');
    }

    public function delete($id)
    {

        $total = Booking::where('tour_id', $id)->count();
        if($total > 0){
            return redirect()->route('admin_tour_index')->with('error', 'This Tour has Bookings. So it can not be deleted.');
        }

        $tour = Tour::where('id', $id)->first();
        $tour->delete();

        // Simpan data lama
        $oldData = $tour->getAttributes();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $oldData ,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\Tour',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Tour di Package ' .$tour->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_tour_index')->with('success', 'Tour berhasil di hapus');
    }

    public function tour_booking($tour_id, $package_id)
    {
        $trashCount = Booking::onlyTrashed()->count();

        $all_data = Booking::with('user')->where('tour_id',$tour_id)->where('package_id',$package_id)->get();

        return view('admin.tour.booking', data: compact('all_data','trashCount'));
    }

    public function tour_booking_delete($id)
    {

        // Simpan data lama
        $tours = Booking::findOrFail($id);

        $oldData = $tours->getAttributes();

        $booking = Booking::where('id', $id)->first();
        $booking->delete();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $oldData,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\Booking',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Booking dengan invoice ' .$booking->invoice_no,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Booking berhasil di hapus');
    }

    public function tour_invoice($invoice_no)
    {
        $booking = Booking::with(['user','tour','package'])->where('invoice_no',$invoice_no)->first();
        return view('admin.tour.invoice', data: compact('booking'));
    }

    public function tour_booking_approve($id)
    {

        // Simpan data lama
        $tours = Booking::findOrFail($id);

        $oldData = $tours->getAttributes();

        Booking::where('id', $id)->update(['payment_status' => 'Completed']);

        //ambil data baru
        $tour = Booking::where('id', $id)->first();
        $newData = $tour->getAttributes();

        // Format log
        $logData = [
            'attributes' => $newData,
            'old' => $oldData,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'updated',
            'subject_type' => 'App\Models\Booking',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menyetujui Booking dengan invoice ' .$tours->invoice_no,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Booking is approved successfully');
    }
}

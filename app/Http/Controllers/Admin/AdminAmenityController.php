<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageAmenity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Amenity;
use Spatie\Activitylog\Models\Activity;

class AdminAmenityController extends Controller
{
    public function index()
    {
        $trashCount = Amenity::onlyTrashed()->count();

        $amenities = Amenity::orderBy('id', 'desc')->get();
        return view('admin.amenity.index', compact('amenities','trashCount'));
    }

    public function create()
    {
        return view('admin.amenity.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:amenities,name',
        ]);

        $amenity = new Amenity();
        $amenity->name = $request->name;
        $amenity->save();


        // Ambil data baru
        $newData = $amenity->getAttributes();

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
            'subject_type' => 'App\Models\Amenity',
            'subject_id' => $amenity->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Manambahkan Amenity ' .$amenity->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_amenity_index')->with('success', 'Amenity berhasil ditambahkan');
    }

    public function edit($id)
    {
        $amenity = Amenity::where('id', $id)->first();
        return view('admin.amenity.edit', compact('amenity'));
    }

    public function edit_submit(Request $request, $id)
    {

        $amenity = Amenity::where('id', $id)->first();

        $request->validate([
            'name' => 'required',
        ]);

        $amenities = Amenity::findOrFail($id);

        // Simpan data lama
        $oldData = $amenities->getAttributes();

        $amenity->name = $request->name;
        $amenity->update();

        // Ambil data baru
        $newData = $amenity->getAttributes();

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
            'subject_type' => 'App\Models\Amenity',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Amenity ' .$amenity->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_amenity_index')->with('success', 'Amenity berhasil di perbarui');
    }

    public function delete($id)
    {

        $amenities = Amenity::findOrFail($id);

        // Simpan data lama
        $oldData = $amenities->getAttributes();

        $total = PackageAmenity::where('amenity_id',$id)->count();
        if ($total > 0) {
            return redirect()->back()->with('error','Amenity is Assigned to Package(s), So ir can not be deleted');
        }

        $amenity = Amenity::where('id', $id)->first();
        $amenity->delete();

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
            'subject_type' => 'App\Models\Amenity',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Amenity ' .$amenity->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_amenity_index')->with('success', 'Amenity berhasil di hapus');
    }
}

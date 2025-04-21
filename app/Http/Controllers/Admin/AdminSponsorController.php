<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AdminSponsorController extends Controller
{
    public function index()
    {
        $trashCount = Sponsor::onlyTrashed()->count();

        $sponsor_photos = Sponsor::get();
        return view('admin.sponsor.index', compact('sponsor_photos','trashCount'));
    }

    public function sponsor_submit(Request $request)
    {
        $request->validate([
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'sponsor_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/'), $final_name);

        $obj = new Sponsor();
        $obj->photo = $final_name;
        $obj->save();

        // Ambil data baru
        $newData = $obj->getAttributes();

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
            'subject_type' => 'App\Models\Sponsor',
            'subject_id' => $obj->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Sponsor ' .$obj->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Photo is Inserted successfully');
    }

    public function sponsor_delete($id)
    {
        $sponsor = Sponsor::where('id', $id)->first();
        File::move(public_path('uploads/'.$sponsor->photo), public_path('trash/').$sponsor->photo);
        $sponsor->delete();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $sponsor->getAttributes(),
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\Sponsor',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Sponsor ' .$sponsor->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Photo berhasil di hapus');
    }
}

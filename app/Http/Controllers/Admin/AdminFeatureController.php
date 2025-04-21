<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Feature;
use Spatie\Activitylog\Models\Activity;

class AdminFeatureController extends Controller
{
    public function index()
    {
        $trashCount = Feature::onlyTrashed()->count();

        $features = Feature::get();
        return view('admin.feature.index', compact('features','trashCount'));
    }

    public function create()
    {
        return view('admin.feature.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'icon' => 'required',
            'heading' =>'required',
            'description' =>'required',
        ]);

        $feature = new Feature();
        $feature->icon = $request->icon;
        $feature->heading = $request->heading;
        $feature->description = $request->description;
        $feature->save();

        // Ambil data baru
        $newData = $feature->getAttributes();

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
            'subject_type' => 'App\Models\Feature',
            'subject_id' => $feature->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Feature ' .$feature->heading,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_feature_index')->with('success', 'Feature berhasil ditambahkan');
    }

    public function edit($id)
    {
        $feature = Feature::where('id', $id)->first();
        return view('admin.feature.edit', compact('feature'));
    }

    public function edit_submit(Request $request, $id)
    {

        $feature = Feature::where('id', $id)->first();

        $request->validate([
            'icon' => 'required',
            'heading' =>'required',
            'description' =>'required',
        ]);

        $features = Feature::findOrFail($id);

        // Simpan data lama
        $oldData = $features->getAttributes();

        $feature->icon = $request->icon;
        $feature->heading = $request->heading;
        $feature->description = $request->description;
        $feature->update();

        // Ambil data baru
        $newData = $feature->getAttributes();

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
            'subject_type' => 'App\Models\Feature',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Feature ' .$feature->heading,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_feature_index')->with('success', 'Feature berhasil di perbarui');
    }

    public function delete($id)
    {

        $features = Feature::findOrFail($id);

        // Simpan data lama
        $oldData = $features->getAttributes();

        $feature = Feature::where('id', $id)->first();
        $feature->delete();

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
            'subject_type' => 'App\Models\Feature',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Feature ' .$feature->heading,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_feature_index')->with('success', 'Feature berhasil di hapus');
    }
}

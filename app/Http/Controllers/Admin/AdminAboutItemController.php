<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AboutItem;
use Spatie\Activitylog\Models\Activity;

class AdminAboutItemController extends Controller
{
    Public function index()
    {
        $about_item = AboutItem::where('id', 1)->first();
        return view('admin.about_item.index', compact('about_item'));
    }

    Public function update(Request $request)
    {

        // Simpan data lama
        $atem = AboutItem::findOrFail(1);
        $oldData = $atem->getAttributes();

        $obj = AboutItem::where('id', 1)->first();
        $obj->featured_status = $request->featured_status;
        $obj->save();

        // Ambil data baru
        $newData = $obj->getAttributes();

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
            'subject_type' => 'App\Models\AboutItem',
            'subject_id' => 1,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit About Item.',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'About Item berhasil di perbarui');
    }
}

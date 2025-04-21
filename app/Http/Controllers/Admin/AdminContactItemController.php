<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ContactItem;
use Spatie\Activitylog\Models\Activity;

class AdminContactItemController extends Controller
{
    Public function index()
    {
        $contact_item = ContactItem::where('id', 1)->first();
        return view('admin.contact_item.index', compact('contact_item'));
    }

    Public function update(Request $request)
    {
        $request->validate([
           'map_code' =>'required',
        ]);

        // Simpan data lama
        $cotem = ContactItem::findOrFail(1);
        $oldData = $cotem->getAttributes();

        $obj = ContactItem::where('id', 1)->first();
        $obj->map_code = $request->map_code;
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
            'subject_type' => 'App\Models\ContactItem',
            'subject_id' => 1,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Contact Item.',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'contact Item berhasil di perbarui');
    }
}

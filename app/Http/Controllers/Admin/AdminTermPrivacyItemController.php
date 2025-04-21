<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TermPrivacyItem;
use Spatie\Activitylog\Models\Activity;

class AdminTermPrivacyItemController extends Controller
{
    Public function index()
    {
        $term_privacy_item = TermPrivacyItem::where('id', 1)->first();
        return view('admin.term_privacy_item.index', compact('term_privacy_item'));
    }

    Public function update(Request $request)
    {

        // Simpan data lama
        $tetem = TermPrivacyItem::findOrFail(1);
        $oldData = $tetem->getAttributes();

        $obj = TermPrivacyItem::where('id', 1)->first();
        $obj->terms = $request->terms;
        $obj->privacy = $request->privacy;
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
            'subject_type' => 'App\Models\TermPrivacyItem',
            'subject_id' => 1,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Term Privacy Item.',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Term Privacy Item berhasil di perbarui');
    }
}

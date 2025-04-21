<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CounterItem;
use Spatie\Activitylog\Models\Activity;

class AdminCounterItemController extends Controller
{
    Public function index()
    {
        $counter_items = CounterItem::where('id', 1)->first();
        return view('admin.counter.index', compact('counter_items'));
    }

    Public function update(Request $request)
    {
        $obj = CounterItem::where('id', 1)->first();

        $request->validate([
            'item1_number' =>'required',
            'item1_text' =>'required',
            'item2_number' =>'required',
            'item2_text' =>'required',
            'item3_number' =>'required',
            'item3_text' =>'required',
            'item4_number' =>'required',
            'item4_text' =>'required',
        ]);

        $cotem = CounterItem::findorFail(1);
        $oldData = $cotem->getAttributes();

        $obj->item1_number = $request->item1_number;
        $obj->item1_text = $request->item1_text;
        $obj->item2_number = $request->item2_number;
        $obj->item2_text = $request->item2_text;
        $obj->item3_number = $request->item3_number;
        $obj->item3_text = $request->item3_text;
        $obj->item4_number = $request->item4_number;
        $obj->item4_text = $request->item4_text;
        $obj->status = $request->status;
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
            'subject_type' => 'App\Models\CounterItem',
            'subject_id' => 1,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Counter Item.',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Counter Item berhasil di perbarui');
    }
}

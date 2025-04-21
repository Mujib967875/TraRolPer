<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\WelcomeItem;
use Spatie\Activitylog\Models\Activity;

class AdminWelcomeItemController extends Controller
{
    Public function index()
    {
        $welcome_items = WelcomeItem::where('id', 1)->first();
        return view('admin.welcome.index', compact('welcome_items'));
    }

    Public function update(Request $request)
    {
        $obj = WelcomeItem::where('id', 1)->first();

        $request->validate([
            'heading' =>'required',
            'description' =>'required',
            'video' =>'required',
        ]);

        // Simpan data lama
        $wetem = WelcomeItem::findOrFail(1);
        $oldData = $wetem->getAttributes();

        if($request->hasFile('photo')){
            $request->validate([
                'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            unlink(public_path('uploads/').$obj->photo);

            $final_name = 'welcome_item_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads/'), $final_name);
            $obj->photo = $final_name;
        }

        $obj->heading = $request->heading;
        $obj->description = $request->description;
        $obj->button_text = $request->button_text;
        $obj->button_link = $request->button_link;
        $obj->video = $request->video;
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
            'subject_type' => 'App\Models\WelcomeItem',
            'subject_id' => 1,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Welcome Item.',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Welcome Item berhasil di perbarui');
    }
}

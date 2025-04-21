<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Setting;
use Spatie\Activitylog\Models\Activity;

class AdminSettingController extends Controller
{
    Public function index()
    {
        $setting = Setting::where('id', 1)->first();
        return view('admin.setting.index', compact('setting'));
    }

    Public function update(Request $request)
    {
        $obj = Setting::where('id', 1)->first();

        // Simpan data lama
        $settings = Setting::findOrFail(1);
        $oldData = $settings->getAttributes();

        if($request->hasFile('logo'))
        {
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if($obj->logo != '')
            {
                unlink(public_path('uploads/').$obj->logo);
            }

            $final_name = 'logo_'.time().'.'.$request->logo->extension();
            $request->logo->move(public_path('uploads'), $final_name);
            $obj->logo = $final_name;
        }

        if($request->hasFile('favicon'))
        {
            $request->validate([
                'favicon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if($obj->favicon != '')
            {
                unlink(public_path('uploads/').$obj->favicon);
            }

            $final_name1 = 'favicon_'.time().'.'.$request->favicon->extension();
            $request->favicon->move(public_path('uploads'), $final_name1);
            $obj->favicon = $final_name1;
        }

        if($request->hasFile('banner'))
        {
            $request->validate([
                'banner' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if($obj->banner != '')
            {
                unlink(public_path('uploads/').$obj->banner);
            }

            $final_name2 = 'banner_'.time().'.'.$request->banner->extension();
            $request->banner->move(public_path('uploads'), $final_name2);
            $obj->banner = $final_name2;
        }

        $obj->top_bar_phone = $request->top_bar_phone;
        $obj->top_bar_email = $request->top_bar_email;
        $obj->footer_address = $request->footer_address;
        $obj->footer_phone = $request->footer_phone;
        $obj->footer_email = $request->footer_email;
        $obj->facebook = $request->facebook;
        $obj->twitter = $request->twitter;
        $obj->youtube = $request->youtube;
        $obj->linkedin = $request->linkedin;
        $obj->instagram = $request->instagram;
        $obj->copyright = $request->copyright;
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
            'subject_type' => 'App\Models\Setting',
            'subject_id' => 1,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Setting.',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Setting berhasil di perbarui');
    }
}

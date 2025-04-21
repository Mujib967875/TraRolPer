<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Slider;
use Spatie\Activitylog\Models\Activity;

class AdminSliderController extends Controller
{
    public function index()
    {
        $trashCount = Slider::onlyTrashed()->count();

        $sliders = Slider::get();
        return view('admin.slider.index', compact('sliders','trashCount'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'heading' =>'required',
            'text' =>'required',
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $finale_name = 'slider'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/'), $finale_name);

        $slider = new Slider();
        $slider->photo = $finale_name;
        $slider->heading = $request->heading;
        $slider->text = $request->text;
        $slider->button_text = $request->button_text;
        $slider->button_link = $request->button_link;
        $slider->save();

        // Ambil data baru
        $newData = $slider->getAttributes();

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
            'subject_type' => 'App\Models\Slider',
            'subject_id' => $slider->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Slider ' .$slider->heading,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_slider_index')->with('success', 'Slider berhasil ditambahkan');
    }

    public function edit($id)
    {
        $slider = Slider::where('id', $id)->first();
        return view('admin.slider.edit', compact('slider'));
    }

    public function edit_submit(Request $request, $id)
    {

        $slider = Slider::where('id', $id)->first();

        $request->validate([
            'heading' =>'required',
            'text' =>'required',
        ]);

        $sliders = Slider::findOrFail($id);

        // Simpan data lama
        $oldData = $sliders->getAttributes();

        if($request->hasFile('photo')){
            $request->validate([
                'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            unlink(public_path('uploads/').$slider->photo);

            $final_name = 'slider_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads/'), $final_name);
            $slider->photo = $final_name;
        }

        $slider->heading = $request->heading;
        $slider->text = $request->text;
        $slider->button_text = $request->button_text;
        $slider->button_link = $request->button_link;
        $slider->update();

        // Ambil data baru
        $newData = $slider->getAttributes();

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
            'subject_type' => 'App\Models\Slider',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Slider ' .$slider->heading,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_slider_index')->with('success', 'Slider berhasil di perbarui');
    }

    public function delete($id)
    {

        $sliders = Slider::findOrFail($id);

        // Simpan data lama
        $oldData = $sliders->getAttributes();

        $slider = Slider::where('id', $id)->first();
        $slider->delete();

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
            'subject_type' => 'App\Models\Slider',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Slider ' .$slider->heading,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_slider_index')->with('success', 'Slider berhasil di hapus');
    }
}

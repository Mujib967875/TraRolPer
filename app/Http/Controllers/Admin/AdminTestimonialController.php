<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Spatie\Activitylog\Models\Activity;

class AdminTestimonialController extends Controller
{
    public function index()
    {
        $trashCount = Testimonial::onlyTrashed()->count();

        $testimonials = Testimonial::get();
        return view('admin.testimonial.index', compact('testimonials','trashCount'));
    }

    public function create()
    {
        return view('admin.testimonial.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'designation' =>'required',
            'comment' =>'required',
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'testimonial_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/'), $final_name);

        $obj = new Testimonial();
        $obj->photo = $final_name;
        $obj->name = $request->name;
        $obj->designation = $request->designation;
        $obj->comment = $request->comment;
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
            'subject_type' => 'App\Models\Testimonial',
            'subject_id' => $obj->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Testimonial ' .$obj->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_testimonial_index')->with('success', 'Testimonial berhasil ditambahkan');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::where('id', $id)->first();
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function edit_submit(Request $request, $id)
    {

        $testimonial = Testimonial::where('id', $id)->first();

        $request->validate([
            'name' =>'required',
            'designation' =>'required',
            'comment' =>'required',
        ]);

         // Simpan data lama
         $tess = Testimonial::findOrFail($id);
         $oldData = $tess->getAttributes();

        if($request->hasFile('photo')){
            $request->validate([
                'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            unlink(public_path('uploads/').$testimonial->photo);

            $final_name = 'testimonial_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads/'), $final_name);
            $testimonial->photo = $final_name;
        }

        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->comment = $request->comment;
        $testimonial->update();

        // Simpan data baru
        $newData = $testimonial->getAttributes();

        // Format Log
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
            'subject_type' => 'App\Models\TeamMember',
            'subject_id' => $testimonial->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Testimonial ' .$testimonial->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_testimonial_index')->with('success', 'Testimonial berhasil di perbarui');
    }

    public function delete($id)
    {
        $testimonial = Testimonial::where('id', $id)->first();
        File::move(public_path('uploads/'.$testimonial->photo), public_path('trash/'.$testimonial->photo));
        $testimonial->delete();

        // Ambil data lama
        $oldData = $testimonial->getAttributes();

        // Format Log
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
            'subject_type' => 'App\Models\Testimonial',
            'subject_id' => $testimonial->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Testimonial ' .$testimonial->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_testimonial_index')->with('success', 'Testimonial berhasil di hapus');
    }
}

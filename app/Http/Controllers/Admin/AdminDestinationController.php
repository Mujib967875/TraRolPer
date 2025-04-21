<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DestinationPhoto;
use App\Models\DestinationVideo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Destination;
use Spatie\Activitylog\Models\Activity;
class AdminDestinationController extends Controller
{
    public function index()
    {
        $trashCount = Destination::onlyTrashed()->count();

        $destinations = Destination::get();
        return view('admin.destination.index', compact('destinations','trashCount'));
    }

    public function create()
    {
        return view('admin.destination.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' =>'required|unique:destinations',
            'slug' =>'required|alpha_dash|unique:destinations',
            'description' =>'required',
            'featured_photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'feaatured_destination_'.time().'.'.$request->featured_photo->extension();
        $request->featured_photo->move(public_path('uploads/'), $final_name);

        $obj = new Destination();
        $obj->featured_photo = $final_name;
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->country = $request->country;
        $obj->language = $request->language;
        $obj->currency = $request->currency;
        $obj->area = $request->area;
        $obj->best_time = $request->best_time;
        $obj->health_safety = $request->health_safety;
        $obj->timezone = $request->timezone;
        $obj->visa_requirement = $request->visa_requirement;
        $obj->activity = $request->activity;
        $obj->map = $request->map;
        // $obj->country_code = strtoupper($request->country_code);
        // $obj->is_visible_on_map = $request->boolean('is_visible_on_map');
        $obj->view_count = 1;
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
            'subject_type' => 'App\Models\Destination',
            'subject_id' => $obj->id,
            'description' => 'Admin Menambahkan Destination ' .$obj->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_destination_index')->with('success', 'Destination berhasil ditambahkan');
    }

    public function edit($id)
    {
        $destination = Destination::where('id', $id)->first();
        return view('admin.destination.edit', compact('destination'));
    }

    public function edit_submit(Request $request, $id)
    {

        $destination = Destination::where('id', $id)->first();

        $request->validate([
            'name' =>'required|unique:destinations,name,'.$id,
            'slug' =>'required|alpha_dash|unique:destinations,slug,'.$id,
            'description' =>'required',
        ]);

        $destinations = Destination::findOrFail($id);

        // Simpan data lama
        $oldData = $destinations->getAttributes();

        if($request->hasFile('featured_photo')){
            $request->validate([
                'featured_photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            unlink(public_path('uploads/').$destination->featured_photo);

            $final_name = 'feaatured_destination_'.time().'.'.$request->featured_photo->extension();
            $request->featured_photo->move(public_path('uploads/'), $final_name);
            $destination->featured_photo = $final_name;
        }

        $destination->name = $request->name;
        $destination->slug = $request->slug;
        $destination->description = $request->description;
        $destination->country = $request->country;
        $destination->language = $request->language;
        $destination->currency = $request->currency;
        $destination->area = $request->area;
        $destination->best_time = $request->best_time;
        $destination->health_safety = $request->health_safety;
        $destination->timezone = $request->timezone;
        $destination->visa_requirement = $request->visa_requirement;
        $destination->activity = $request->activity;
        $destination->map = $request->map;
        $destination->update();

        // Ambil data baru
        $newData = $destination->getAttributes();

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
            'subject_type' => 'App\Models\Destination',
            'subject_id' => $id,
            'description' => 'Admin Mengedit Destination ' .$destination->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_destination_index')->with('success', 'Destination berhasil di perbarui');
    }

    public function delete(Request $request, $id)
    {

        $destinations = Destination::findOrFail($id);

        // Simpan data lama
        $oldData = $destinations->getAttributes();

        $total = DestinationPhoto::where('destination_id', $id)->count();
        if($total > 0){
            return redirect()->back()->with('error', 'First delete all photos of this destination');
        }

        $total1 = DestinationVideo::where('destination_id', $id)->count();
        if($total > 0){
            return redirect()->back()->with('error', 'First delete all videos of this destination');
        }

        $destination = Destination::where('id', $id)->first();
        File::move(public_path('uploads/'.$destination->featured_photo), public_path('trash/'.$destination->featured_photo));
        $destination->delete();

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
            'subject_type' => 'App\Models\Destination',
            'subject_id' => $id,
            'description' => 'Admin Menghapus Destination ' .$destination->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_destination_index')->with('success', 'Destination berhasil di hapus');
    }


    public function destination_photos($id)
    {
        $trashCount = DestinationPhoto::onlyTrashed()->count();

        $destination = Destination::with('photos')->where('id', $id)->first();
        $destination_photos = DestinationPhoto::where('destination_id', $id)->get();
        return view('admin.destination.photos', compact('destination','destination_photos','trashCount'));
    }

    public function destination_photo_submit(Request $request, $id)
    {
        $request->validate([
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'destination_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/'), $final_name);

        $obj = new DestinationPhoto();
        $obj->destination_id = $id;
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
            'subject_type' => 'App\Models\DestinationPhoto',
            'subject_id' => $obj->id,
            'description' => 'Admin Menambahkan Destination Photo di ',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Photo is Inserted successfully');
    }

    public function destination_photo_delete($id)
    {

        $destinations = DestinationPhoto::findOrFail($id);

        // Simpan data lama
        $oldData = $destinations->getAttributes();

        $photo = DestinationPhoto::where('id', $id)->first();
        File::move(public_path('uploads/'.$photo->photo), public_path('trash/'.$photo->photo));
        $photo->delete();

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
            'subject_type' => 'App\Models\DestinationPhoto',
            'subject_id' => $id,
            'description' => 'Admin Menghapus Destination Photo',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Photo berhasil di hapus');
    }


    public function destination_videos($id)
    {
        $trashCount = DestinationVideo::onlyTrashed()->count();

        $destination = Destination::with('videos')->where('id', $id)->first();
        $destination_videos = DestinationVideo::where('destination_id', $id)->get();
        return view('admin.destination.videos', compact('destination','destination_videos','trashCount'));
    }

    public function destination_video_submit(Request $request, $id)
    {
        $request->validate([
            'video' => 'required'
        ]);

        $obj = new DestinationVideo();
        $obj->destination_id = $id;
        $obj->video = $request->video;
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
            'subject_type' => 'App\Models\DestinationVideo',
            'subject_id' => $obj->id,
            'description' => 'Admin Menambahkan Destination Video di ',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Video is Inserted successfully');
    }

    public function destination_video_delete($id)
    {

        $destinations = DestinationVideo::findOrFail($id);

        // Simpan data lama
        $oldData = $destinations->getAttributes();

        $destination_video = DestinationVideo::where('id',$id)->first();
        $destination_video->delete();

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
            'subject_type' => 'App\Models\DestinationVideo',
            'subject_id' => $id,
            'description' => 'Admin Menghapus Destination Video ',
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Video berhasil di hapus');
    }
}

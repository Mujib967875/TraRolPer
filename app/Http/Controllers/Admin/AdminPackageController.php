<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\PackageAmenity;
use App\Models\PackageFaq;
use App\Models\PackageItinerary;
use App\Models\PackagePhoto;
use App\Models\PackageVideo;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageAmenityPhoto;
use Spatie\Activitylog\Models\Activity;

class AdminPackageController extends Controller
{
    public function index()
    {
        $trashCount = Package::onlyTrashed()->count();

        $packages = Package::get();
        return view('admin.package.index', compact('packages','trashCount'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('name', 'asc')->get();
        return view('admin.package.create', compact('destinations'));
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:packages',
            'slug' => 'required|alpha_dash|unique:packages',
            'description' => 'required',
            'price' => 'required|numeric',
            'old_price' => 'numeric',
            'featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'featured_package_' . time() . '.' . $request->featured_photo->extension();
        $request->featured_photo->move(public_path('uploads/'), $final_name);

        $final_name_banner = 'banner_package_' . time() . '.' . $request->banner->extension();
        $request->banner->move(public_path('uploads/'), $final_name_banner);

        $obj = new Package();
        $obj->featured_photo = $final_name;
        $obj->banner = $final_name_banner;
        $obj->destination_id = $request->destination_id;
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->price = $request->price;
        $obj->old_price = $request->old_price;
        $obj->map = $request->map;
        $obj->total_rating = 0;
        $obj->total_score = 0;
        $obj->min_person = $request->min_person ?? 1;
        $obj->max_person = $request->max_person ?? 100;
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
            'subject_type' => 'App\Models\Package',
            'subject_id' => $obj->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Package ' .$obj->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_package_index')->with('success', 'Package is berhasil ditambahkan');
    }

    public function edit($id)
    {
        $package = Package::where('id', $id)->first();
        $destinations = Destination::orderBy('name', 'asc')->get();
        return view('admin.package.edit', compact('package', 'destinations'));
    }

    public function edit_submit(Request $request, $id)
    {
        $package = Package::where('id', $id)->first();

        $request->validate([
            'name' => 'required|unique:packages,name,' . $id,
            'slug' => 'required|alpha_dash|unique:packages,slug,' . $id,
            'description' => 'required',
            'price' => 'required|numeric',
            'old_price' => 'numeric',
        ]);

        $packages = Package::findOrFail($id);

        // Simpan data lama
        $oldData = $packages->getAttributes();

        if ($request->hasFile('featured_photo')) {
            $request->validate([
                'featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            unlink(public_path('uploads/') . $package->featured_photo);
            unlink(public_path('uploads/') . $package->banner);

            $final_name = 'featured_package_' . time() . '.' . $request->featured_photo->extension();
            $request->featured_photo->move(public_path('uploads/'), $final_name);
            $package->featured_photo = $final_name;
        }

        if ($request->hasFile('banner')) {
            $request->validate([
                'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $final_name1 = 'banner_package_' . time() . '.' . $request->banner->extension();
            $request->banner->move(public_path('uploads/'), $final_name1);
            $package->banner = $final_name1;
        }

        $package->destination_id = $request->destination_id;
        $package->name = $request->name;
        $package->slug = $request->slug;
        $package->description = $request->description;
        $package->price = $request->price;
        $package->old_price = $request->old_price;
        $package->map = $request->map;
        $package->min_person = $request->min_person;
        $package->max_person = $request->max_person;
        $package->update();

        // Ambil data baru
        $newData = $package->getAttributes();

        // Format log
        $logData = [
            'attributes' => $newData,
            'old' => $oldData ,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'updated',
            'subject_type' => 'App\Models\Package',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Package ' .$package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_package_index')->with('success', 'Package berhasil di perbarui');
    }

    public function delete($id)
    {

        $packages = Package::findOrFail($id);

        // Simpan data lama
        $oldData = $packages->getAttributes();

        $total = PackagePhoto::where('package_id', $id)->count();
        if($total > 0){
            return redirect()->back()->with('error', 'First delete all photos of this Package');
        }

        $total1 = PackageVideo::where('package_id', $id)->count();
        if($total1 > 0){
            return redirect()->back()->with('error', 'First delete all videos of this Package');
        }

        $total2 = PackageAmenity::where('package_id', $id)->count();
        if($total2 > 0){
            return redirect()->back()->with('error', 'First delete all Amenities of this package');
        }

        $total3 = PackageItinerary::where('package_id', $id)->count();
        if($total3 > 0){
            return redirect()->back()->with('error', 'First delete all Itineraries of this package');
        }

        $total4 = PackageFaq::where('package_id', $id)->count();
        if($total4 > 0){
            return redirect()->back()->with('error', 'First delete all FAQs of this package');
        }

        $total5 = Tour::where('package_id', $id)->count();
        if($total5 > 0){
            return redirect()->back()->with('error', 'First delete all Tours of this package');
        }

        $package = Package::where('id', $id)->first();
        File::move(public_path('uploads/'.$package->featured_photo),public_path('trash/').$package->featured_photo);
        File::move(public_path('uploads/'.$package->banner),public_path('trash/').$package->banner);
        $package->delete();

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
            'subject_type' => 'App\Models\Package',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Package ' .$package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_package_index')->with('success', 'Package berhasil di hapus');
    }

    public function package_amenities($id)
    {
        $trashCount = PackageAmenity::onlyTrashed()->count();

        $package = Package::where('id', $id)->first();

        $package_amenities_includes = PackageAmenity::with( 'photo')
            ->where('package_id', $id)
            ->where('type', 'include')
            ->get();

        $package_amenities_excludes = PackageAmenity::with( 'photo')
            ->where('package_id', $id)
            ->where('type', 'exclude')
            ->get();



        $amenities = Amenity::orderBy('name','asc')->get();

        return view('admin.package.amenities', compact('package','package_amenities_includes','package_amenities_excludes','amenities','trashCount'));
    }

    public function package_amenities_submit(Request $request, $id)
    {
        $total = PackageAmenity::where('package_id',$id)->where('amenities_name',$request->amenities_name)->count();

        if($total){
            return redirect()->back()->with('error','Fasilitas Sudah di Tambahkan!');
        }

        $obj = new PackageAmenity();
        $obj->package_id = $id;
        $obj->amenities_name = $request->amenities_name;
        $obj->type = $request->type;
        $obj->save();

        // Ambil data baru
        $newData = $obj->getAttributes();
        $PN = Package::where('id', $id)->first();

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
            'subject_type' => 'App\Models\PackageAmenity',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Amenity di Package ' .$PN->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Item is Inserted successfully');
    }

    public function package_amenities_delete($id)
    {

        $amenities = PackageAmenity::findOrFail($id);

        // Simpan data lama
        $oldData = $amenities->getAttributes();

        $amenity = PackageAmenity::find($id);

        $isCheckedOut = Booking::where('package_id', $amenity->package_id)
            ->whereIn('payment_status', ['Completed', 'completed'])
            ->exists();

        if ($isCheckedOut) {
            return redirect()->back()->with('error', 'Cannot delete amenities because the package has been checked out');
        } else {
            $amenity->delete();
        }


        $PN = Package::where('id', $amenity->package_id)->first();

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
            'subject_type' => 'App\Models\PackageAmenity',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Amenity di Package ' .$PN->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Item berhasil di hapus');
    }




    public function package_amenity_photos($package_id, $amenity_id)
    {
        $package = Package::find($package_id);
        $package_amenities = PackageAmenity::find($amenity_id);
        $package_amenity_photos = PackageAmenityPhoto::where('package_id', $package_id)->where('package_amenity_id', $amenity_id)->get();

        return view('admin.package.amenity_photos', compact('package_amenities','package_amenity_photos','package'));
    }


    public function package_amenity_photos_submit(Request $request, $package_id, $amenity_id)
    {
        $request->validate([
            'photo' =>'required|mimes:jpeg,png,jpg,gif,svg,webp,avif',
        ]);

        $final_name = 'package_amenity'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/'), $final_name);

        $obj = new PackageAmenityPhoto();
        $obj->package_id = $package_id;
        $obj->package_amenity_id = $amenity_id;
        $obj->photo = $final_name;
        $obj->save();

        return redirect()->back()->with('success', 'Foto Fasilitas Berhasil ditambahkan');
    }


    public function package_amenity_photos_delete($id)
    {
        $photo = PackageAmenityPhoto::findOrFail($id);

        $filePath = public_path('uploads/' . $photo->photo);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $photo->delete();

        return redirect()->back()->with('success', 'Foto Fasilitas berhasil dihapus');
    }






    public function package_itineraries($id)
    {
        $trashCount = PackageItinerary::onlyTrashed()->count();

        $package = Package::where('id', $id)->first();
        $package_itineraries = PackageItinerary::where('package_id', $id)->get();
        return view('admin.package.itineraries', compact('package','package_itineraries','trashCount'));
    }

    public function package_itineraries_submit(Request $request, $id)
    {
        $obj = new PackageItinerary();
        $obj->package_id = $id;
        $obj->name = $request->name;
        $obj->description = $request->description;
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
            'subject_type' => 'App\Models\PackageItinerary',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Itinerary di Package ' .$obj->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Item is Inserted successfully');
    }

    public function package_itineraries_delete($id)
    {

        $itineraries = PackageItinerary::findOrFail($id);

        // Simpan data lama
        $oldData = $itineraries->getAttributes();

        $obj = PackageItinerary::where('id', $id)->first();
        $obj->delete();

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
            'subject_type' => 'App\Models\PackageItinerary',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Itinerary Package ' .$obj->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return redirect()->back()->with('success', 'Item berhasil di hapus');
    }

    public function package_photos($id)
    {
        $trashCount = PackagePhoto::onlyTrashed()->count();

        $package = Package::with('package_photos')->where('id', $id)->first();
        $package_photos = PackagePhoto::where('package_id', $id)->get();
        return view('admin.package.photos', compact('package','package_photos','trashCount'));
    }

    public function package_photos_submit(Request $request, $id)
    {
        $request->validate([
            'photo' =>'required|mimes:jpeg,png,jpg,gif,svg,webp,avif',
        ]);

        $final_name = 'package_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/'), $final_name);

        $obj = new PackagePhoto();
        $obj->package_id = $id;
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
            'subject_type' => 'App\Models\PackagePhoto',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Photo di Package ' .$obj->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Photo is Inserted successfully');
    }

    public function package_photos_delete($id)
    {

        $photos = PackagePhoto::findOrFail($id);

        // Simpan data lama
        $logData = $photos->getAttributes();

        $photo = PackagePhoto::where('id', $id)->first();
        File::move(public_path('uploads/'.$photo->photo),public_path('trash/'.$photo->photo));
        $photo->delete();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $logData,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\PackagePhoto',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Photo di Package ' .$photo->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Photo berhasil di hapus');
    }

    public function package_videos($id)
    {
        $trashCount = PackageVideo::onlyTrashed()->count();

        $package = Package::with('package_videos')->where('id', $id)->first();
        $package_videos = PackageVideo::where('package_id', $id)->get();
        return view('admin.package.videos', compact('package','package_videos','trashCount'));
    }

    public function package_videos_submit(Request $request, $id)
    {
        $request->validate([
            'video' => 'required'
        ]);

        $obj = new PackageVideo();
        $obj->package_id = $id;
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
            'subject_type' => 'App\Models\PackageVideo',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Video di Package ' .$obj->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Video is Inserted successfully');
    }

    public function package_videos_delete($id)
    {

        $videos = PackageVideo::findOrFail($id);

        // Simpan data lama
        $logData = $videos->getAttributes();

        $package_video = PackageVideo::where('id',$id)->first();
        $package_video->delete();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $logData,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\PackageVideo',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Video di Package ' .$package_video->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Video berhasil di hapus');
    }

    public function package_faqs($id)
    {
        $trashCount = PackageFaq::onlyTrashed()->count();

        $package = Package::with('package_faqs')->where('id', $id)->first();
        $package_faqs = PackageFaq::where('package_id', $id)->get();
        return view('admin.package.faqs', compact('package','package_faqs','trashCount'));
    }

    public function package_faqs_submit(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $obj = new PackageFaq();
        $obj->package_id = $id;
        $obj->question = $request->question;
        $obj->answer = $request->answer;
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
            'subject_type' => 'App\Models\PackageFaq',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan FAQ di Package ' .$obj->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'FAQ is Inserted successfully');
    }

    public function package_faqs_delete($id)
    {

        $faqs = PackageFaq::findOrFail($id);

        // Simpan data lama
        $logData = $faqs->getAttributes();

        $package_faq = PackageFaq::where('id',$id)->first();
        $package_faq->delete();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $logData,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\PackageFaq',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus FAQ di Package ' .$package_faq->package->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'FAQ berhasil di hapus');
    }
}

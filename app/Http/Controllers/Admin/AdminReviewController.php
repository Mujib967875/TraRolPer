<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Review;
use Spatie\Activitylog\Models\Activity;

class AdminReviewController extends Controller
{
    public function index()
    {
        $trashCount = Review::onlyTrashed()->count();

        $reviews = Review::with(['user','package'])->get();
        return view('admin.review.index', compact('reviews','trashCount'));
    }

    public function delete($id)
    {

        $reviews = Review::findOrFail($id);

        // Simpan data lama
        $oldData = $reviews->getAttributes();

        $obj = Review::where('id', $id)->first();
        $rating = $obj->rating;
        $package_id = $obj->package_id;
        $obj->delete();

        $package_data = Package::where('id',$package_id)->first();
        $updated_total_rating = $package_data->total_rating - 1;
        $updated_total_score = $package_data->total_score - $rating;
        $package_data->total_rating = $updated_total_rating;
        $package_data->total_score = $updated_total_score;
        $package_data->save();

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
            'subject_type' => 'App\Models\Review',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Review ' .$obj->comment,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success','Review berhasil di hapus');
    }
}

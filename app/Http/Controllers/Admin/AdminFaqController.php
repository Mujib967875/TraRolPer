<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Faq;
use Spatie\Activitylog\Models\Activity;
class AdminFaqController extends Controller
{
    public function index()
    {
        $trashCount = Faq::onlyTrashed()->count();

        $faqs = Faq::get();
        return view('admin.faq.index', compact('faqs','trashCount'));
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' =>'required',
        ]);

        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        // Ambil data baru
        $newData = $faq->getAttributes();

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
            'subject_type' => 'App\Models\Faq',
            'subject_id' => $faq->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Faq ' .$faq->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_faq_index')->with('success', 'Faq berhasil ditambahkan');
    }

    public function edit($id)
    {
        $faq = Faq::where('id', $id)->first();
        return view('admin.faq.edit', compact('faq'));
    }

    public function edit_submit(Request $request, $id)
    {

        $faq = Faq::where('id', $id)->first();

        $request->validate([
            'question' => 'required',
            'answer' =>'required',
        ]);

        // Simpan data baru
        $faqs = Faq::findOrFail($id);
        $oldData = $faqs->getAttributes();

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->update();

        // Ambil data baru
        $newData = $faq->getAttributes();

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
            'subject_type' => 'App\Models\Faq',
            'subject_id' => $faq->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Faq ' .$faq->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_faq_index')->with('success', 'Faq berhasil di perbarui');
    }

    public function delete($id)
    {
        $faq = Faq::where('id', $id)->first();
        $faq->delete();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $faq->getAttributes(),
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\Faq',
            'subject_id' => $faq->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Faq ' .$faq->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_faq_index')->with('success', 'Faq berhasil di hapus');
    }
}

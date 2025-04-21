<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use Spatie\Activitylog\Models\Activity;

class AdminSubscriberController extends Controller
{
    public function subscribers()
    {
        $trashCount = Subscriber::onlyTrashed()->count();

        $subscribers = Subscriber::where('status','Active')->get();
        return view('admin.subscribers.index', compact('subscribers', 'trashCount'));
    }

    public function send_email()
    {
        return view('admin.subscribers.send_email');
    }

    public function send_email_submit(Request $request)
    {
        $request->validate([
           'subject' =>'required',
           'message' =>'required',
        ]);

        $subject = $request->subject;
        $message = $request->message;

        $all_subs = Subscriber::where('status','Active')->get();
        foreach ($all_subs as $item) {
            Mail::to($item->email)->send(new Websitemail($subject, $message));

            // Format log
            $logData = [
                'attributes' => 'Null',
                'old' => 'Null',
            ];

            // Simpan log menggunakan insert
            Activity::insert([
                'causer_id' => Auth::guard('admin')->user()->id,
                'causer_type' => 'App\Models\Admin',
                'log_name' => 'default',
                'event' =>'send email',
                'subject_type' => 'App\Models\Subscriber',
                'subject_id' => $item->id,
                'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengirim email ke semua subscriber ',
                'properties' => json_encode($logData), // Simpan dalam format JSON
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }

        return redirect()->back()->with('success', 'Email is sent successfully.');
    }

    public function subscriber_delete($id)
    {

        // Ambil data lama
        $oldData = Subscriber::where('id',$id)->first();

        $subscriber = Subscriber::where('id',$id)->first();
        $subscriber->delete();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $oldData->getAttributes(),
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'delete subscriber',
            'subject_type' => 'App\Models\Subscriber',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus subscriber ' .$subscriber->email,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Subscriber berhasil di hapus.');
    }
}

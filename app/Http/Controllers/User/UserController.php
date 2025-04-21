<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Message;
use App\Models\MessageComment;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function dashboard()
    {
        $total_completed_orders = Booking::where('user_id', Auth::guard('web')->user()->id)->where('payment_status', 'Completed')->count();
        $total_pending_orders = Booking::where('user_id', Auth::guard('web')->user()->id)->where('payment_status', 'Pending')->count();
        return view('user.dashboard',compact('total_completed_orders','total_pending_orders'));
    }

    public function wishlist()
    {
        $wishlists = Wishlist::with('package')->where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'desc')->paginate(10);
        return view('user.wishlist',compact('wishlists'));
    }

    public function wishlist_delete($id)
    {
        $wishlist = Wishlist::where('id', $id)->where('user_id', Auth::guard('web')->user()->id)->first();
        if ($wishlist) {
            $wishlist->delete();
            return redirect()->back()->with('success', 'Wishlist berhasil di hapus');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function message(){
        $message_check = Message::where('user_id', Auth::guard('web')->user()->id)->first();
        $message = Message::where('user_id', Auth::guard('web')->user()->id)->first();
        if ($message) {
            $message_comments = MessageComment::where('message_id', $message->id)->orderBy('id', 'desc')->get();
        } else {
            $message_comments = [];
        }

        return view('user.message',compact('message_check','message_comments'));
    }

    public function message_start()
    {
        $message_check = Message::where('user_id', Auth::guard('web')->user()->id)->count();
        if ($message_check > 0) {
            return redirect()->back()->with('error', 'You have already started the conversation');
        }

        $obj =  new Message();
        $obj->user_id = Auth::guard('web')->user()->id;
        $obj->save();

        return redirect()->back();
    }

    public function message_submit(Request $request)
    {
        $request->validate([
           'comment' =>'required',
        ]);

        $message = Message::where('user_id', Auth::guard('web')->user()->id)->first();

        $obj =  new MessageComment();
        $obj->message_id = $message->id;
        $obj->sender_id = Auth::guard('web')->user()->id;
        $obj->type = 'User';
        $obj->comment = $request->comment;
        $obj->save();

        $message_link = route('admin_message_detail', $message->id);
        $subject = 'New User Message';
        $message = 'Please click on the following link to see the new message from the user:<br><a href="'.$message_link.'">See Message</a>';

        $admin_data = Admin::where('id', 1)->first();

        Mail::to($admin_data->email)->send(new Websitemail($subject, $message));

        return redirect()->back()->with('success', 'Message sent');
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function profile_submit(Request $request)
    {

        $user = User::where('id', Auth::guard('web')->user()->id)->first();

        $request->validate([
            'name' =>'required',
            'email' =>'required|email|unique:users,email,'.$user->id,
            'phone' =>'required',
            'country' =>'required',
            'address' =>'required',
            'city' =>'required',
            'state' =>'required',
            'zip' =>'required',
            ]);

            if ($request->photo) {
                $request->validate([
                    'photo' => ['mimes:jpeg,png,jpg,gif','max:2048'],
                ]);

                if ($user->photo) {
                    unlink(public_path('uploads/').$user->photo);
                }

                $final_name = 'user_photo_' . time() . '.' . $request->photo->extension();
                $request->photo->move(public_path('uploads/'), $final_name);
                $user->photo = $final_name;
            }

            $request->validate([
                'password' => 'nullable|min:8',
                'retype_password' => 'nullable|required_with:password|same:password|min:8',
            ]);
    
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            // if ($request->password) {
            //     $request->validate([
            //         'password' => ['required'],
            //         'retype_password' => ['required','same:password'],
            //     ]);

            //     $user->password = bcrypt($request->password);
            // }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->country = $request->country;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->zip = $request->zip;
            $user->save();

            return redirect()->back()->with('success', 'Profile berhasil di perbarui');
    }

    public function booking()
    {
        $all_data = Booking::with(['tour','package'])->where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'desc')->paginate(10);
        return view('user.booking',compact('all_data'));
    }

    public function invoice($invoice_no)
    {
        $admin_data = Admin::where('id', 1)->first();
        $booking = Booking::with(['tour','package'])->where('invoice_no', $invoice_no)->first();
        return view('user.invoice',compact('invoice_no','booking','admin_data'));
    }

    public function review(){
        $reviews = Review::with('package')->where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'desc')->paginate(10);
        return view('user.review',compact('reviews'));
    }
}

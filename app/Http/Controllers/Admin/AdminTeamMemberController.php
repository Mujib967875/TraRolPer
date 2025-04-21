<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use Spatie\Activitylog\Models\Activity;

class AdminTeamMemberController extends Controller
{
    public function index()
    {
        $trashCount = TeamMember::onlyTrashed()->count();

        $team_members = TeamMember::get();
        return view('admin.team.index', compact('team_members','trashCount'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'slug' =>'required|alpha_dash|unique:team_members',
            'designation' =>'required',
            'email' =>'required',
            'address' =>'required',
            'phone' =>'required',
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'team_member_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/'), $final_name);

        $obj = new TeamMember();
        $obj->photo = $final_name;
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->designation = $request->designation;
        $obj->email = $request->email;
        $obj->address = $request->address;
        $obj->phone = $request->phone;
        $obj->biography = $request->biography;
        $obj->facebook = $request->facebook;
        $obj->twitter = $request->twitter;
        $obj->linkedin = $request->linkedin;
        $obj->instagram = $request->instagram;
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
            'subject_type' => 'App\Models\TeamMember',
            'subject_id' => $obj->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Team Member ' .$obj->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_team_member_index')->with('success', 'Team member berhasil ditambahkan');
    }

    public function edit($id)
    {
        $team_member = TeamMember::where('id', $id)->first();
        return view('admin.team.edit', compact('team_member'));
    }

    public function edit_submit(Request $request, $id)
    {

        $obj = TeamMember::where('id', $id)->first();

        $request->validate([
            'name' =>'required',
            'slug' =>'required|alpha_dash|unique:team_members,slug,'.$obj->id,
            'designation' =>'required',
            'email' =>'required',
            'address' =>'required',
            'phone' =>'required',
        ]);

        // Simpan data lama
        $teams = TeamMember::findOrFail($id);
        $oldData = $teams->getAttributes();

        if($request->hasFile('photo')){
            $request->validate([
                'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            unlink(public_path('uploads/').$obj->photo);

            $final_name = 'team_member_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads/'), $final_name);
            $obj->photo = $final_name;
        }

        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->designation = $request->designation;
        $obj->email = $request->email;
        $obj->address = $request->address;
        $obj->phone = $request->phone;
        $obj->biography = $request->biography;
        $obj->facebook = $request->facebook;
        $obj->twitter = $request->twitter;
        $obj->linkedin = $request->linkedin;
        $obj->instagram = $request->instagram;
        $obj->update();

        // Simpan data baru
        $newData = $obj->getAttributes();

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
            'subject_id' => $obj->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Team Member ' .$obj->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_team_member_index')->with('success', 'Team member berhasil di perbarui');
    }
    public function delete($id)
    {
        $team_member = TeamMember::where('id', $id)->first();
        File::move(public_path('uploads/'.$team_member->photo), public_path('trash/'.$team_member->photo));
        $team_member->delete();

        // Ambil data lama
        $oldData = $team_member->getAttributes();

        // Format Log
        $logData = [
            'attributes' => 'null',
            'old' => $oldData,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\TeamMember',
            'subject_id' => $team_member->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Team Member ' .$team_member->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_team_member_index')->with('success', 'Team member berhasil di hapus');
    }
}

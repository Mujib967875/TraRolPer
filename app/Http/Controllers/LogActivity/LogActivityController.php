<?php

namespace App\Http\Controllers\LogActivity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogActivityController extends Controller
{
    public function indexAdmin(Request $request){
        $adminlogs = Activity::with('causer')->where('causer_type', 'App\Models\Admin')->latest()->get();
        return view('admin.logactivity.index_admin', compact('adminlogs'));
    }

    public function indexUser(Request $request){
        $userlogs = Activity::with('causer')->where('causer_type', 'App\Models\User')->latest()->get();
        return view('admin.logactivity.index_user', compact('userlogs'));
    }
}

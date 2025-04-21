<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Package;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\BookingExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $total_subscribers = Subscriber::where('status','Active')->count();
        $total_users = User::where('status',1)->count();
        $total_testimonials = Testimonial::count();
        $total_destinations = Destination::count();
        $total_team_members = TeamMember::count();
        $total_packages = Package::count();
        $total_sliders = Slider::count();
        $total_posts = Post::count();
        $total_tours = Tour::count();

        $year = request()->get('year', date('Y'));

        $monthlyIncome = Booking::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(paid_amount) as total_income')
            )
            ->whereYear('created_at', $year)
            ->where('payment_status', 'Completed')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_income', 'month');

        return view('admin.dashboard.dashboard', compact('total_sliders', 'total_testimonials', 'total_team_members', 'total_posts', 'total_destinations', 'total_packages', 'total_users', 'total_subscribers', 'total_tours','monthlyIncome','year'));
    }

}

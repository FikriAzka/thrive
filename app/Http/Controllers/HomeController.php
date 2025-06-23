<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Meeting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get current month data
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Count meetings this month
        $meetingsThisMonth = Meeting::whereMonth('tanggal_mulai', $currentMonth)
            ->whereYear('tanggal_mulai', $currentYear)
            ->count();
        
        // Count completed meetings this month (as notulensi)
        $notesThisMonth = Meeting::whereMonth('tanggal_mulai', $currentMonth)
            ->whereYear('tanggal_mulai', $currentYear)
            ->where('status', 'completed')
            ->count();
        
        // Get meetings for this month
        $meetingsData = Meeting::whereMonth('tanggal_mulai', $currentMonth)
            ->whereYear('tanggal_mulai', $currentYear)
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
        
        // Get upcoming meetings (tasks)
        $upcomingMeetings = Meeting::where('tanggal_mulai', '>=', Carbon::now()->toDateString())
            ->where('status', 'scheduled')
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(10)
            ->get();
        
        // Get overdue meetings
        $overdueMeetings = Meeting::where('tanggal_mulai', '<', Carbon::now()->toDateString())
            ->where('status', 'scheduled')
            ->orderBy('tanggal_mulai', 'desc')
            ->limit(10)
            ->get();
        
        // Get progress data (recent meetings with different statuses)
        $progressMeetings = Meeting::orderBy('tanggal_mulai', 'desc')
            ->limit(10)
            ->get();
        
        return view('home', compact(
            'meetingsThisMonth',
            'notesThisMonth', 
            'meetingsData',
            'upcomingMeetings',
            'overdueMeetings',
            'progressMeetings'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // Count meetings this month
        $meetingsThisMonth = Meeting::thisMonth()->count();
        
        // Count completed meetings this month (as notulensi)
        $notesThisMonth = Meeting::thisMonth()->completed()->count();
        
        // Get meetings for this month
        $meetingsData = Meeting::thisMonth()
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
        
        // Get upcoming meetings
        $upcomingMeetings = Meeting::upcoming()
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(10)
            ->get();
        
        // Get overdue meetings
        $overdueMeetings = Meeting::overdue()
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
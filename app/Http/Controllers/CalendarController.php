<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function showCalendar()
    {
        $email = Auth::user()->email; // Ambil email user yang login
        
        return view('calendar', compact('email')); // Kirim ke view
    }
}

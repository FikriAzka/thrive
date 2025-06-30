<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RatingController extends Controller
{
    
    
    public function index()
    {
        $ratings = Rating::all();
        $neutral = $ratings->where('pertanyaan1', 'Neutral')->count();
        $very_satisfied = $ratings->where('pertanyaan1', 'Very Satisfied')->count();

        return view('ratings.index', compact('ratings', 'neutral', 'very_satisfied'));
    }

    public function showDataForm(Meeting $meeting) // Pastikan $meeting adalah model
    {
        if (!Cache::get("allow_ratings_{$meeting->id}", true) && !Auth::check()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('ratings.create', compact('meeting'));
    }


    public function toggleAccess(Request $request, Meeting $meeting)
    {
        Cache::put("allow_ratings_{$meeting->id}", $request->allow_ratings, now()->addHours(1));

        return response()->json([
            'message' => 'Pengaturan akses berhasil diperbarui.',
        ]);
    }

    public function storeData(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:ratings,email',
            'phone' => 'required|regex:/^[0-9]+$/|max:20|unique:ratings,phone',
            'position' => 'required|string|max:255',
            'project_or_product' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
        ]);

        // Simpan data ke session
        $request->session()->put('data_diri', $validated);
        $request->session()->put('meeting_id', $meeting->id);

        return redirect()->route('ratings.form', $meeting);
    }

    
    public function showRatingForm(Request $request, Meeting $meeting)
    {
        $dataDiri = $request->session()->get('data_diri');
        $sessionMeetingId = $request->session()->get('meeting_id');

        if (!$dataDiri || $sessionMeetingId != $meeting->id) {
            return redirect()->route('ratings.create', $meeting)
                           ->with('error', 'Harap isi data diri terlebih dahulu.');
        }

        return view('ratings.rating', compact('dataDiri', 'meeting'));
    }

    public function showRatingForm2(Request $request, Meeting $meeting)
    {
        $dataDiri = $request->session()->get('data_diri');
        $ratingData = $request->session()->get('rating_data');
        $sessionMeetingId = $request->session()->get('meeting_id');

        if (!$dataDiri || $sessionMeetingId != $meeting->id) {
            return redirect()->route('ratings.create', $meeting)
                           ->with('error', 'Harap isi data diri terlebih dahulu.');
        }

        return view('ratings.rating2', compact('dataDiri', 'meeting'));
    }

    public function storeRating(Request $request, Meeting $meeting)
    {
        try {
            $validated = $request->validate([
                'pertanyaan1' => 'required|integer|min:1|max:5',
                'pertanyaan2' => 'required|integer|min:1|max:5',
                'pertanyaan3' => 'required|integer|min:1|max:5',
                'pertanyaan4' => 'required|integer|min:1|max:5',
                'pertanyaan5' => 'required|integer|min:1|max:5',
                'suggestions' => 'nullable|string|max:2000',
            ]);

            // Store the first page data in session
            $request->session()->put('rating_data', $validated);

            return redirect()->route('ratings.form2', $meeting);
        } catch (\Exception $e) {
            Log::error('Rating Store Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeFinalRating(Request $request, Meeting $meeting)
    {
        try {
            $validated = $request->validate([
                'pertanyaan6' => 'required|integer|min:1|max:5',
                'pertanyaan7' => 'required|integer|min:1|max:5',
                'pertanyaan8' => 'required|integer|min:1|max:5',
                'suggestions2' => 'nullable|string|max:2000',
            ]);

            $dataDiri = $request->session()->get('data_diri');
            $ratingData = $request->session()->get('rating_data');
            $sessionMeetingId = $request->session()->get('meeting_id');
            
            if (!$dataDiri || !$ratingData || $sessionMeetingId != $meeting->id) {
                return redirect()->route('ratings.create', $meeting)
                            ->with('error', 'Data tidak lengkap. Silakan isi kembali.');
            }

            $dataToSave = array_merge(
                $dataDiri,
                $ratingData,
                $validated,
                ['meeting_id' => $meeting->id]
            );

            $rating = Rating::create($dataToSave);

            // Clear all session data
            $request->session()->forget(['data_diri', 'meeting_id', 'rating_data']);
            
            // Cek apakah pengguna login atau tidak
            if (Auth::check()) {
                return redirect()->route('ratings.index')
                    ->with('success', 'Terima kasih atas feedback Anda!');
            } else {
                return redirect()->route('ratings.create')
                    ->with('success', 'Terima kasih atas feedback Anda!');
            }
        } catch (\Exception $e) {
            Log::error('Rating Store Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
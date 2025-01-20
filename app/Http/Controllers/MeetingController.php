<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Meeting;
use Google\Service\Calendar;

use Illuminate\Http\Request;
use App\Mail\MeetingInvitation;
use App\Models\MeetingParticipant;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MeetingController extends Controller
{

    protected $googleMeetController;

    public function __construct(GoogleMeetController $googleMeetController)
    {
        $this->googleMeetController = $googleMeetController;
    }
    public function index(Request $request)
    {
        

        return view('meetings.index_rapat');
    }

    public function create()
    {
        $users = User::all();
        return view('meetings.buat_rapat', compact('users'));
    }

    public function complete(Meeting $meeting)
    {
        // Memperbarui status rapat menjadi 'completed'
        $meeting->update(['status' => 'completed']);
        
        // Mengalihkan ke halaman daftar rapat dengan pesan sukses
        return redirect()->route('meetings.index')->with('success', 'Meeting marked as completed');
    }


    public function getUsers(Request $request)
    {
        $search = $request->name;
        
        if ($search) {
            $peserta = User::where('name', 'LIKE', "%$search%")->get();
        } else {
            $peserta = User::all();
        }
        
        return response()->json($peserta);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rapat' => 'required|string',
            'jenis_rapat' => 'required|in:offline,online',
            'google_meet_link' => 'nullable|url',
            'google_event_id' => 'nullable|string',
            'agenda_rapat' => 'required|string',
            'tempat_rapat' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required',
            'jam_berakhir' => 'required|after:jam_mulai',
            'catatan' => 'nullable|string',
            'nama_pic' => 'required',
            'peserta' => 'required',
        ]);

        $validated['peserta']=json_encode($request->peserta);
        $validated['nama_pic']=json_encode($request->nama_pic);
        
        $meeting = Meeting::create($validated);

        foreach ($request->peserta as $pesertaId) {
            MeetingParticipant::create([
                'meeting_id' => $meeting->id,
                'user_id' => $pesertaId,
            ]);
    
        }

        // Kirim email ke PIC
        // Menyimpan data PIC (Penanggung Jawab)
        foreach ($request->nama_pic as $picId) {
            // Menyimpan data PIC ke dalam MeetingParticipant
            MeetingParticipant::create([
                'meeting_id' => $meeting->id,
                'user_id' => $picId,
            ]);
        }
        

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil dibuat');
    }


    public function show(Meeting $meeting)
    {
        // Eager load participants with related user
        $participants = MeetingParticipant::with('user')
            ->where('meeting_id', $meeting->id) // Use $meeting->id to get the current meeting's ID
            ->get();

        return view('meetings.show', compact('meeting', 'participants'));
    }



    public function edit(Meeting $meeting)
    {
        $users = User::all(); 

        return view('meetings.edit', compact('meeting', 'users'));
    }



    public function update(Request $request, Meeting $meeting)
    {
        Log::info('Update request received', ['request' => $request->all()]);

        try {
            $validated = $request->validate([
                'nama_rapat' => 'required|string',
                'status' => 'required|string',
                'jenis_rapat' => 'required|in:offline,online',
                'google_meet_link' => 'nullable|url',
                'google_event_id' => 'nullable|string',
                'agenda_rapat' => 'required|string',
                'tempat_rapat' => 'nullable|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
                'jam_mulai' => 'required',
                'jam_berakhir' => 'required|after:jam_mulai',
                'catatan' => 'nullable|string',
                'nama_pic' => 'required',
                'peserta' => 'required',
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            if ($meeting->jenis_rapat === 'online' && $meeting->google_event_id) {
                try {
                    $googleMeetResponse = $this->googleMeetController->updateGoogleMeet($meeting, $request);
                    $responseData = json_decode($googleMeetResponse->getContent(), true);
                    
                    if (!$responseData['success']) {
                        Log::error('Failed to update Google Meet', $responseData);
                        return redirect()
                            ->back()
                            ->with('error', $responseData['message'])
                            ->withInput();
                    }
                } catch (\Exception $e) {
                    Log::error('Exception when updating Google Meet', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return redirect()
                        ->back()
                        ->with('error', 'Gagal mengupdate Google Meet: ' . $e->getMessage())
                        ->withInput();
                }
            }

            // Update data di database
            $validated['peserta'] = json_encode($request->peserta);
            $validated['nama_pic'] = json_encode($request->nama_pic);
            
            $meeting->update($validated);
            Log::info('Meeting updated in database', ['meeting_id' => $meeting->id]);

            // Update meeting participants
            MeetingParticipant::where('meeting_id', $meeting->id)->delete();
            Log::info('Old participants deleted');

            // Tambah participants baru
            foreach ($request->peserta as $pesertaId) {
                MeetingParticipant::create([
                    'meeting_id' => $meeting->id,
                    'user_id' => $pesertaId,
                ]);
            }
            Log::info('New participants added');

            // Tambah PIC baru
            foreach ($request->nama_pic as $picId) {
                MeetingParticipant::create([
                    'meeting_id' => $meeting->id,
                    'user_id' => $picId,
                ]);
            }
            Log::info('New PICs added');

            return redirect()->route('meetings.index')->with('success', 'Rapat berhasil diupdate');

        } catch (\Exception $e) {
            Log::error('Error in update method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function destroy(Meeting $meeting)
    {
        if ($meeting->jenis_rapat === 'online' && $meeting->google_event_id) {
            try {
                $googleMeetResponse = $this->googleMeetController->deleteGoogleMeet($meeting->google_event_id);
                $responseData = json_decode($googleMeetResponse->getContent(), true);
                
                if (!$responseData['success']) {
                    return redirect()->back()->with('error', 'Gagal menghapus Google Meet');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal menghapus Google Meet: ' . $e->getMessage());
            }
        }
        // Hapus meeting participants
            MeetingParticipant::where('meeting_id', $meeting->id)->delete();
            
            // Hapus meeting
            $meeting->delete();
            return redirect()->route('meetings.index')->with('success', 'Rapat berhasil dihapus');
        
    }


    public function ratingPage()
    {        
        return view('rating');
    }

    public function saveNotes(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id); // Ambil data rapat berdasarkan ID
        $meeting->notes = $request->input('notes'); // Perbarui field `notes`
        $meeting->save(); // Simpan perubahan ke database

        return redirect()->back()->with('success', 'Notulensi berhasil diperbarui!');
    }


    // use Barryvdh\DomPDF\Facade\Pdf;

    // public function download(Meeting $meeting)
    // {
    //     $pdf = PDF::loadView('meetings.pdf', compact('meeting'));
    //     return $pdf->download('notulensi-rapat-' . $meeting->id . '.pdf');
    // }

    

}

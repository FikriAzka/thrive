<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'nama_rapat',
        'jenis_rapat',
        'google_meet_link',
        'google_event_id',
        'agenda_rapat',
        'tempat_rapat',
        'tanggal_mulai',
        'tanggal_berakhir',
        'jam_mulai',
        'jam_berakhir',
        'catatan',
        'nama_pic',
        'peserta',
        'status',
        'attachment',
        'meet_creation_status',
        'meet_creation_error'

    ];

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }
}

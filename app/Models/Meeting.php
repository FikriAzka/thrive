<?php

namespace App\Models;

use Carbon\Carbon;
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
        'attachment_link'
    ];

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    protected $dates = [
        'tanggal_mulai',
        'tanggal_berakhir'
    ];

    // Accessor to get formatted date
    public function getFormattedStartDateAttribute()
    {
        return Carbon::parse($this->tanggal_mulai)->format('d-M-Y');
    }

    public function getFormattedEndDateAttribute()
    {
        return Carbon::parse($this->tanggal_berakhir)->format('d-M-Y');
    }

    // Check if meeting is overdue
    public function getIsOverdueAttribute()
    {
        return Carbon::parse($this->tanggal_mulai)->isPast() && $this->status === 'scheduled';
    }

    // Check if meeting is upcoming
    public function getIsUpcomingAttribute()
    {
        return Carbon::parse($this->tanggal_mulai)->isFuture() && $this->status === 'scheduled';
    }

    // Get days difference from today
    public function getDaysFromNowAttribute()
    {
        $meetingDate = Carbon::parse($this->tanggal_mulai);
        $now = Carbon::now();
        
        if ($meetingDate->isFuture()) {
            return $meetingDate->diffInDays($now) . ' hari lagi';
        } elseif ($meetingDate->isPast()) {
            return $meetingDate->diffInDays($now) . ' hari yang lalu';
        } else {
            return 'Hari ini';
        }
    }

    // Get status label with color
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'completed':
                return ['text' => 'Selesai', 'class' => 'text-success'];
            case 'cancelled':
                return ['text' => 'Dibatalkan', 'class' => 'text-danger'];
            case 'scheduled':
                if ($this->is_overdue) {
                    return ['text' => 'Sudah Lewat', 'class' => 'text-danger'];
                } else {
                    return ['text' => 'Terjadwal', 'class' => 'text-info'];
                }
            default:
                return ['text' => 'Unknown', 'class' => 'text-muted'];
        }
    }
}

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

    protected $dates = [
        'tanggal_mulai',
        'tanggal_berakhir'
    ];

    protected $casts = [
        'nama_pic' => 'json',
        'peserta' => 'json',
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date'
    ];

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function picUsers()
    {
        return $this->belongsToMany(User::class, 'meeting_pics', 'meeting_id', 'user_id');
    }

    public function participantUsers()
    {
        return $this->belongsToMany(User::class, 'meeting_participants', 'meeting_id', 'user_id');
    }
    
    // Accessor to get formatted date
    public function getFormattedStartDateAttribute()
    {
        return $this->tanggal_mulai ? $this->tanggal_mulai->format('d-M-Y') : '-';
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->tanggal_berakhir ? $this->tanggal_berakhir->format('d-M-Y') : '-';
    }

    // Check if meeting is overdue
    public function getIsOverdueAttribute()
    {
        return $this->tanggal_mulai && $this->tanggal_mulai->isPast() && $this->status === 'scheduled';
    }

    // Check if meeting is upcoming
    public function getIsUpcomingAttribute()
    {
        return $this->tanggal_mulai && $this->tanggal_mulai->isFuture() && $this->status === 'scheduled';
    }

    // Get days difference from today
    public function getDaysFromNowAttribute()
    {
        if (!$this->tanggal_mulai) return '-';
        
        $meetingDate = $this->tanggal_mulai;
        $now = Carbon::now()->startOfDay();
        
        if ($meetingDate->isToday()) {
            return 'Hari ini';
        } elseif ($meetingDate->isTomorrow()) {
            return 'Besok';
        } elseif ($meetingDate->isYesterday()) {
            return 'Kemarin';
        } elseif ($meetingDate->isFuture()) {
            return $meetingDate->diffInDays($now) . ' hari lagi';
        } else {
            return $meetingDate->diffInDays($now) . ' hari yang lalu';
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
            case 'ongoing':
                return ['text' => 'Sedang Berlangsung', 'class' => 'text-warning'];
            default:
                return ['text' => 'Unknown', 'class' => 'text-muted'];
        }
    }

    // Get PIC names (untuk JSON data)
    public function getPicNamesAttribute()
    {
        if (is_array($this->nama_pic)) {
            // Jika nama_pic berisi array of user IDs
            $users = User::whereIn('id', $this->nama_pic)->pluck('name')->toArray();
            return implode(', ', $users);
        } elseif (is_string($this->nama_pic)) {
            // Jika nama_pic berisi string langsung
            return $this->nama_pic;
        }
        
        return '-';
    }

    // Get participant names (untuk JSON data)
    public function getParticipantNamesAttribute()
    {
        if (is_array($this->peserta)) {
            // Jika peserta berisi array of user IDs
            $users = User::whereIn('id', $this->peserta)->pluck('name')->toArray();
            return implode(', ', $users);
        } elseif (is_string($this->peserta)) {
            // Jika peserta berisi string langsung
            return $this->peserta;
        }
        
        return '-';
    }

    // Get PIC count
    public function getPicCountAttribute()
    {
        if (is_array($this->nama_pic)) {
            return count($this->nama_pic);
        }
        return 0;
    }

    // Get participant count
    public function getParticipantCountAttribute()
    {
        if (is_array($this->peserta)) {
            return count($this->peserta);
        }
        return 0;
    }

    // Scopes untuk query
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal_mulai', Carbon::now()->month)
                    ->whereYear('tanggal_mulai', Carbon::now()->year);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>=', Carbon::now()->toDateString())
                    ->where('status', 'scheduled');
    }

    public function scopeOverdue($query)
    {
        return $query->where('tanggal_mulai', '<', Carbon::now()->toDateString())
                    ->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
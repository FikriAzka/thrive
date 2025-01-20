<?php

namespace App\Livewire;

use App\Models\Meeting;
use Livewire\Component;
use Livewire\WithPagination;

class MeetingTable extends Component
{
    public $search = '';
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        return view('livewire.meeting', [
            'meetings' => Meeting::query()
                ->where(function($query) {
                    $query->where('nama_rapat', 'LIKE', '%'.$this->search.'%')
                          ->orWhere('agenda_rapat', 'LIKE', '%'.$this->search.'%');
                })
                ->orderBy('tanggal_mulai', 'desc')
                ->orderBy('jam_mulai', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(5)
        ]);
    }

    public function updatingSearch(){
        $this->resetPage();
    }
}

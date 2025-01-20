<div class="container-fluid vh-100 p-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Rapat</h4>
                <a href="{{ route('meetings.create') }}" class="btn btn-primary ms-auto">
                    <i class="fas fa-plus"></i> Buat Rapat
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-outline-secondary" onclick="window.location.reload();">
                    <i class="fas fa-sync-alt"></i> Reload
                </button>
                <input type="text" class="form-control" wire:model.live="search" placeholder="Cari Rapat"
                    style="width: auto;">
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-nowrap" width="5%">No</th>
                            <th class="text-nowrap" width="15%">Nama Rapat</th>
                            <th class="text-nowrap" width="25%">Agenda Rapat</th>
                            <th class="text-nowrap" width="10%">Status</th>
                            <th class="text-nowrap" width="10%">Tanggal</th>
                            <th class="text-nowrap" width="15%">Waktu</th>
                            <th class="text-nowrap" width="10%">Jenis Rapat</th>
                            <th class="text-nowrap" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($meetings as $index => $meeting)
                            <tr>
                                <td>{{ $meetings->firstItem() + $index }}</td>
                                <td>{{ Str::limit($meeting->nama_rapat, 30) }}</td>
                                <td>{{ Str::limit(strip_tags($meeting->agenda_rapat), 100) }}</td>
                                <td>
                                    @if ($meeting->status == 'scheduled')
                                        <span class="badge rounded-pill bg-warning">Scheduled</span>
                                    @elseif ($meeting->status == 'completed')
                                        <span class="badge rounded-pill bg-success">Completed</span>
                                    @elseif ($meeting->status == 'cancelled')
                                        <span class="badge rounded-pill bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($meeting->tanggal_mulai)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($meeting->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($meeting->jam_berakhir)->format('H:i') }}</td>
                                <td>{{ ucfirst($meeting->jenis_rapat) }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('meetings.edit', $meeting->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('meetings.destroy', $meeting) }}" method="POST"
                                            style="display:inline;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                {{ $meetings->links() }}
            </div>
        </div>
    </div>

</div>

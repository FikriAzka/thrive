@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-6">
            <!-- Number of tasks box -->
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Rapat Bulan Ini</span>
                    <span class="info-box-number">{{ $meetingsThisMonth }}</span>
                </div>
                <div class="info-box-footer">
                    <a href="#" class="text-muted">LIHAT RAPAT</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Number of notes box -->
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-edit"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Notulensi Rapat Bulan Ini</span>
                    <span class="info-box-number">{{ $notesThisMonth }}</span>
                </div>
                <div class="info-box-footer">
                    <a href="#" class="text-muted">LIHAT NOTULENSI</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rapat Bulanan list box -->
    <div class="card">
        <div class="card-body p-0">
            <!-- Container with left icon -->
            <div class="d-flex">
                <!-- Left icon section -->
                <div class="bg-danger p-4 d-flex align-items-center justify-content-center"
                    style="min-height: 100%; margin: 10px; border-radius: 5px;">
                    <i class="fas fa-users text-white fa-2x"></i>
                </div>

                <!-- Content section -->
                <div class="p-3 flex-grow-1">
                    <h5 class="font-weight-bold mb-3">Rapat Bulan Ini</h5>

                    <div>
                        @forelse($meetingsData->take(5) as $meeting)
                            <div class="mb-2">
                                <span class="font-weight-bold">{{ $meeting->nama_rapat }}</span>
                                <span class="{{ $meeting->status_label['class'] }}">
                                    ({{ $meeting->status_label['text'] }})
                                </span>
                                <span class="text-muted">{{ $meeting->formatted_start_date }}</span>
                                @if($meeting->jenis_rapat === 'online')
                                    <span class="badge badge-info ml-1">Online</span>
                                @else
                                    <span class="badge badge-secondary ml-1">Offline</span>
                                @endif
                            </div>
                        @empty
                            <div class="text-muted">Belum ada rapat bulan ini</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tugas Rapat list box -->
    <div class="card">
        <div class="card-body p-0">
            <!-- Container with left icon -->
            <div class="d-flex">
                <!-- Left icon section -->
                <div class="p-4 d-flex align-items-center justify-content-center"
                    style="min-height: 100%; margin: 10px; border-radius: 5px; background-color: #ff7e1a;">
                    <i class="fas fa-edit text-white fa-2x"></i>
                </div>

                <!-- Content section -->
                <div class="p-3 flex-grow-1">
                    <h5 class="font-weight-bold mb-3">Rapat Mendatang & Terlambat</h5>

                    <div>
                        <!-- Upcoming Meetings -->
                        @foreach($upcomingMeetings->take(3) as $meeting)
                            <div class="mb-2 text-info">
                                <span class="font-weight-bold">{{ $meeting->nama_rapat }}</span>
                                <span>({{ $meeting->days_from_now }})</span>
                                <span class="text-muted">{{ $meeting->formatted_start_date }}</span>
                                <small class="text-muted d-block">PIC: {{ $meeting->pic_names }}</small>
                            </div>
                        @endforeach

                        <!-- Overdue Meetings -->
                        @foreach($overdueMeetings->take(3) as $meeting)
                            <div class="mb-2 text-danger">
                                <span class="font-weight-bold">{{ $meeting->nama_rapat }}</span>
                                <span>(Sudah Lewat & Belum Selesai)</span>
                                <span class="text-muted">{{ $meeting->formatted_start_date }}</span>
                                <small class="text-muted d-block">PIC: {{ $meeting->pic_names }}</small>
                            </div>
                        @endforeach

                        @if($upcomingMeetings->isEmpty() && $overdueMeetings->isEmpty())
                            <div class="text-muted">Tidak ada rapat mendatang atau terlambat</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Button for "Lihat Lebih Banyak" -->
            <div class="d-flex justify-content-end mt-1">
                <button class="btn btn-link text-primary">Lihat Lebih Banyak</button>
            </div>
        </div>
    </div>

    <!-- Progress Rapat box -->
    <div class="card">
        <div class="card-body p-0">
            <!-- Container with left icon -->
            <div class="d-flex">
                <!-- Left icon section -->
                <div class="p-4 d-flex align-items-center justify-content-center"
                    style="min-height: 100%; margin: 10px; border-radius: 5px; background-color: #28a745;">
                    <i class="fas fa-chart-line text-white fa-2x"></i>
                </div>

                <!-- Content section -->
                <div class="p-3 flex-grow-1">
                    <h5 class="font-weight-bold mb-3">Progress Rapat Terbaru</h5>

                    <div>
                        @forelse($progressMeetings->take(5) as $meeting)
                            <div class="mb-2">
                                <span class="font-weight-bold">{{ $meeting->nama_rapat }}</span>
                                <span class="{{ $meeting->status_label['class'] }}">
                                    ({{ $meeting->status_label['text'] }})
                                </span>
                                <span class="text-muted">{{ $meeting->formatted_start_date }}</span>
                                <small class="text-muted d-block">
                                    PIC: {{ $meeting->pic_names }} | 
                                    @if($meeting->tempat_rapat)
                                        Tempat: {{ $meeting->tempat_rapat }}
                                    @elseif($meeting->jenis_rapat === 'online')
                                        Online Meeting
                                    @else
                                        Tempat: -
                                    @endif
                                </small>
                                @if($meeting->participant_count > 0)
                                    <small class="text-info d-block">
                                        <i class="fas fa-users fa-sm"></i> 
                                        {{ $meeting->participant_count }} Peserta
                                    </small>
                                @endif
                            </div>
                        @empty
                            <div class="text-muted">Belum ada data rapat</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
    <script>
        console.log("Dashboard loaded with dynamic data!");
        
        // Auto refresh dashboard every 5 minutes
        setTimeout(function(){
            location.reload();
        }, 300000);
    </script>
@stop
@extends('adminlte::page')
@section('title', 'Show Rapat')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Show Rapat</h1>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 mr-3">Status : @if ($meeting->status == 'scheduled')
                            <span class="badge rounded-pill bg-warning">Scheduled</span>
                        @elseif ($meeting->status == 'completed')
                            <span class="badge rounded-pill bg-success">Completed</span>
                        @elseif ($meeting->status == 'cancelled')
                            <span class="badge rounded-pill bg-danger">Cancelled</span>
                        @endif
                    </h5>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>

            </div>
        </div>
        <div class="card-body">
            <p><strong>Nama Rapat:</strong> {{ $meeting->nama_rapat }}</p>
            <!-- Jenis Rapat -->
            <p><strong>Jenis Rapat:</strong> {{ ucfirst($meeting->jenis_rapat) }}</p>

            <!-- Tampilkan tempat rapat hanya jika jenis rapatnya offline -->
            @if ($meeting->jenis_rapat === 'offline')
                <p><strong>Tempat Rapat:</strong> {{ $meeting->tempat_rapat }}</p>
            @endif

            <!-- Tampilkan link Google Meet hanya jika jenis rapatnya online -->
            @if ($meeting->jenis_rapat === 'online')
                <p><strong>Google Meet Link:</strong>
                    <a href="{{ $meeting->google_meet_link }}" target="_blank">
                        {{ $meeting->google_meet_link }}
                    </a>
                </p>
            @endif
            <p><strong>Agenda Rapat:</strong> {{ strip_tags($meeting->agenda_rapat) }}</p>
            <p><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($meeting->tanggal_mulai)->format('d M Y') }}</p>
            <p><strong>Tanggal Berakhir:</strong> {{ \Carbon\Carbon::parse($meeting->tanggal_berakhir)->format('d M Y') }}
            </p>
            <p><strong>Jam Mulai:</strong> {{ $meeting->jam_mulai }}</p>
            <p><strong>Jam Berakhir:</strong> {{ $meeting->jam_berakhir }}</p>
            <p><strong>Catatan:</strong> {{ strip_tags($meeting->catatan) }}</p>

            <!-- Nama PIC -->
            <!-- Nama PIC -->
            <h6 class="mt-3"><strong>Nama PIC:</strong></h6>
            <ul>
                @foreach ($namaPicUsers as $pic)
                    <li>{{ $pic->name }}</li>
                @endforeach
            </ul>

            <!-- Peserta -->
            <!-- Peserta -->
            <h6 class="mt-3"><strong>Peserta:</strong></h6>
            <ul>
                @foreach ($pesertaUsers as $peserta)
                    <li>{{ $peserta->name }}</li>
                @endforeach
            </ul>
            @if ($meeting->attachment)
                <div class="mb-4">
                    <h6>Attachment:</h6>
                    @php
                        $extension = pathinfo($meeting->attachment, PATHINFO_EXTENSION);
                    @endphp

                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $meeting->attachment) }}" class="img-fluid"
                            style="max-width: 100%">
                    @elseif ($extension == 'pdf')
                        <div style="height: 800px;">
                            <embed src="{{ asset('storage/' . $meeting->attachment) }}" type="application/pdf"
                                width="100%" height="100%">
                        </div>
                    @elseif (in_array($extension, ['doc', 'docx']))
                        <iframe
                            src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $meeting->attachment)) }}"
                            width="100%" height="800px" frameborder="0">
                        </iframe>
                    @elseif (in_array($extension, ['xls', 'xlsx']))
                        <iframe
                            src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $meeting->attachment)) }}"
                            width="100%" height="800px" frameborder="0">
                        </iframe>
                    @else
                        <div class="alert alert-info">
                            File type not supported for direct preview
                        </div>
                    @endif
                </div>
            @endif

            <div class="text-right mt-4">
                <form action="{{ route('meetings.complete', $meeting->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Done</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Attachment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('meetings.complete', $meeting->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="attachment">File Attachment</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" required>
                            <small class="form-text text-muted">Supported formats: PDF, JPG, PNG, JPEG, DOC, DOCX, XLS,
                                XLSX</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

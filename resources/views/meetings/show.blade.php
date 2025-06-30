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
            <!-- Previous content remains the same until attachment section -->
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
                @foreach (json_decode($meeting->nama_pic, true) ?? [] as $picId)
                    @php $pic = \App\Models\User::find($picId); @endphp
                    @if ($pic)
                        <li>{{ $pic->name }}</li>
                    @endif
                @endforeach
            </ul>

            <h6 class="mt-3"><strong>Peserta:</strong></h6>
            <ul>
                @foreach (json_decode($meeting->peserta, true) ?? [] as $pesertaId)
                    @php $peserta = \App\Models\User::find($pesertaId); @endphp
                    @if ($peserta)
                        <li>{{ $peserta->name }}</li>
                    @endif
                @endforeach
            </ul>


            <div class="mb-4">
                <h5>Attachments MoM :</h5>

                @if ($meeting->attachment_link)
                    <div class="mb-3">
                        <strong>Link:</strong>
                        <div class="alert alert-info">
                            <i class="fas fa-link mr-2"></i>
                            <a href="{{ $meeting->attachment_link }}" target="_blank">{{ $meeting->attachment_link }}</a>
                        </div>
                    </div>
                @endif

                @if ($meeting->attachment)
                    <div class="mb-3">
                        <strong>File:</strong>
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
                        @else
                            <div class="alert alert-info">
                                <a href="{{ asset('storage/' . $meeting->attachment) }}" target="_blank"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="text-right mt-4 d-flex justify-content-end align-items-center">
                <!-- Copy Link Section -->
                <div class="input-group mr-3" style="max-width: 400px;">
                    <input type="text" id="ratingLink" value="{{ route('ratings.create', $meeting) }}"
                        class="form-control" readonly>
                    <div class="input-group-append">
                        <button onclick="copyRatingLink()" class="btn btn-outline-secondary" type="button"
                            title="Copy Rating Link">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Done Button Form -->
                <form action="{{ route('meetings.complete', $meeting->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Done</button>
                </form>
            </div>

            <script>
                function copyRatingLink() {
                    const linkInput = document.getElementById('ratingLink');
                    linkInput.select();
                    document.execCommand('copy');

                    // Show feedback tooltip using Bootstrap
                    const copyButton = linkInput.nextElementSibling.querySelector('button');
                    const originalHtml = copyButton.innerHTML;
                    copyButton.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => {
                        copyButton.innerHTML = originalHtml;
                    }, 2000);
                }
            </script>
        </div>
    </div>

    <!-- Updated Upload Modal -->
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
                <form action="{{ route('meetings.upload', $meeting->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        {{-- Menampilkan error validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="attachment">File Attachment</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                id="attachment" name="attachment">
                            <small class="form-text text-muted">Supported formats: PDF, JPG, PNG, JPEG (Max: 2MB)</small>
                        </div>

                        <div class="form-group">
                            <label for="attachment_link">Attachment Link</label>
                            <input type="url" class="form-control @error('attachment_link') is-invalid @enderror"
                                id="attachment_link" name="attachment_link" placeholder="https://example.com/document"
                                value="{{ old('attachment_link') }}">
                            <small class="form-text text-muted">Optional: Add a link to external document/resource</small>
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

@section('js')
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#uploadModal').modal('show');
            });
        </script>
    @endif

@stop

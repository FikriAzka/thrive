@extends('adminlte::page')

@section('title', 'Edit Pengajuan Rapat')

@section('content_header')
    <h1>Edit Pengajuan Rapat</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Pengajuan Rapat</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Rapat -->
                <div class="form-group row">
                    <label for="nama_rapat" class="col-sm-3 col-form-label">Nama Rapat <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('nama_rapat') is-invalid @enderror" id="nama_rapat"
                            name="nama_rapat" value="{{ old('nama_rapat', $meeting->nama_rapat) }}" required>
                        @error('nama_rapat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="scheduled" {{ old('status', $meeting->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="cancelled" {{ old('status', $meeting->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ old('status', $meeting->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Mulai -->
                <div class="form-group row">
                    <label for="tanggal_mulai" class="col-sm-3 col-form-label">Tanggal Mulai <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                id="tanggal_mulai" name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', $meeting->tanggal_mulai) }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tanggal Berakhir -->
                <div class="form-group row">
                    <label for="tanggal_berakhir" class="col-sm-3 col-form-label">Tanggal Berakhir <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="date" class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                                id="tanggal_berakhir" name="tanggal_berakhir"
                                value="{{ old('tanggal_berakhir', $meeting->tanggal_berakhir) }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            @error('tanggal_berakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Jam Mulai -->
                <div class="form-group row">
                    <label for="jam_mulai" class="col-sm-3 col-form-label">Jam Mulai <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai"
                            name="jam_mulai" min="07:00" max="20:00"
                            value="{{ old('jam_mulai', $meeting->jam_mulai) }}" required>
                        @error('jam_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Jam Berakhir -->
                <div class="form-group row">
                    <label for="jam_berakhir" class="col-sm-3 col-form-label">Jam Berakhir <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="time" class="form-control @error('jam_berakhir') is-invalid @enderror"
                            id="jam_berakhir" name="jam_berakhir" min="07:00" max="20:00"
                            value="{{ old('jam_berakhir', $meeting->jam_berakhir) }}" required>
                        @error('jam_berakhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Agenda Rapat -->
                <div class="form-group row">
                    <label for="agenda_rapat" class="col-sm-3 col-form-label">Agenda Rapat <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control @error('agenda_rapat') is-invalid @enderror" id="agenda_rapat" name="agenda_rapat"
                            rows="3">{{ old('agenda_rapat', $meeting->agenda_rapat) }}</textarea>
                        @error('agenda_rapat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <!-- Catatan -->
                <div class="form-group row">
                    <label for="catatan" class="col-sm-3 col-form-label">Catatan</label>
                    <div class="col-sm-9">
                        <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="2">{{ old('catatan', $meeting->catatan) }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Nama PIC -->
                <div class="form-group row">
                    <label for="nama_pic" class="col-sm-3 col-form-label">Nama PIC <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="nama_pic form-control" name="nama_pic[]" id="nama_pic" multiple="multiple"
                            style="height: 38px;">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if (in_array($user->id, json_decode($meeting->nama_pic, true) ?? [])) selected @endif>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('nama_pic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Peserta -->
                <div class="form-group row">
                    <label for="peserta" class="col-sm-3 col-form-label">Peserta Rapat <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="peserta form-control" name="peserta[]" id="peserta" multiple="multiple"
                            style="height: 38px;">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if (in_array($user->id, json_decode($meeting->peserta, true) ?? [])) selected @endif>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('peserta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Jenis Rapat -->
                <div class="form-group row">
                    <label for="jenis_rapat" class="col-sm-3 col-form-label">Jenis Rapat <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control @error('jenis_rapat') is-invalid @enderror" id="jenis_rapat"
                            name="jenis_rapat" required>
                            <option value="">---Pilih Jenis Rapat---</option>
                            <option value="offline"
                                {{ old('jenis_rapat', $meeting->jenis_rapat) == 'offline' ? 'selected' : '' }}>Offline
                            </option>
                            <option value="online"
                                {{ old('jenis_rapat', $meeting->jenis_rapat) == 'online' ? 'selected' : '' }}>Online
                            </option>
                        </select>
                        @error('jenis_rapat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Google Meet Link (hanya muncul jika online) -->
                <div class="form-group row" id="google_meet_section"
                    style="{{ $meeting->jenis_rapat == 'online' ? '' : 'display: none;' }}">
                    <label for="google_meet_link" class="col-sm-3 col-form-label">Link Google Meet</label>
                    <div class="col-sm-9">
                        <input type="url" class="form-control @error('google_meet_link') is-invalid @enderror"
                            id="google_meet_link" name="google_meet_link"
                            value="{{ old('google_meet_link', $meeting->google_meet_link) }}" disabled>
                        @error('google_meet_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tempat Rapat -->
                <div class="form-group row" id="tempat_rapat_section"
                    style="{{ $meeting->jenis_rapat == 'offline' ? '' : 'display: none;' }}">
                    <label for="tempat_rapat" class="col-sm-3 col-form-label">Tempat Rapat</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('tempat_rapat') is-invalid @enderror"
                            id="tempat_rapat" name="tempat_rapat"
                            value="{{ old('tempat_rapat', $meeting->tempat_rapat) }}">
                        @error('tempat_rapat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tombol -->
                <div class="form-group row">
                    <div class="col-sm-12 text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!--select2-->
    <script>
        $(document).ready(function() {
            $('.peserta').select2({
                placeholder: 'Pilih peserta rapat',
                allowClear: true,

            });

            $("#peserta").select2({
                ajax: {
                    url: "{{ route('get-users') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            name: params.
                            term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },

                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                }
                            })
                        };
                    },
                },
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.nama_pic').select2({
                placeholder: 'Pilih Nama PIC',
                allowClear: true,

            });

            $("#nama_pic").select2({
                ajax: {
                    url: "{{ route('get-users') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            name: params.
                            term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },

                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                }
                            })
                        };
                    },
                },
            });
        });
    </script>

    <!--ckeditor textarea-->
    <script>
        const {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph
        } = CKEDITOR;

        // Fungsi untuk menginisialisasi editor
        const initializeEditor = (selector) => {
            ClassicEditor
                .create(document.querySelector(selector), {
                    licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Njc5MTY3OTksImp0aSI6ImQ0YTdhMWVmLTM5MGItNGRhYi1iNTg1LWFhNmEzOWQ3YjEyMiIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCJdLCJ2YyI6ImI0Njc1NWU3In0.3p7AY9a3fj4AurrTrdBw_qa27RH99OoDsSj_6sK0DB1XKCyE_961SnbdkDZ5hyhdFVrtyCfoqEfPUlSb6xV_dA',
                    plugins: [Essentials, Bold, Italic, Font, Paragraph],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                    ]
                })
                .catch(error => {
                    console.error(`Error initializing editor for ${selector}:`, error);
                });
        };

        // Inisialisasi editor untuk #agenda_rapat
        initializeEditor('#agenda_rapat');

        // Inisialisasi editor untuk #catatan
        initializeEditor('#catatan');
    </script>
@stop

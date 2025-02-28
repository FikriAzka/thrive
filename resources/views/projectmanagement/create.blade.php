@extends('adminlte::page')

@section('title', 'Buat Proyek Baru')

@section('content_header')
    <h1>Buat Proyek Baru</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('projectmanagement.store') }}" method="POST">
                @csrf

                <!-- Nama Proyek -->
                <div class="form-group row">
                    <label for="nama_proyek" class="col-sm-3 col-form-label">Nama Proyek <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('nama_proyek') is-invalid @enderror"
                            id="nama_proyek" name="nama_proyek" value="{{ old('nama_proyek') }}"
                            placeholder="Masukkan nama proyek">
                        @error('nama_proyek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="form-group row">
                    <label for="description" class="col-sm-3 col-form-label">Deskripsi <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" placeholder="Masukkan deskripsi proyek">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group row">
                    <label for="status" class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Deadline -->
                <div class="form-group row">
                    <label for="deadline" class="col-sm-3 col-form-label">Deadline <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control @error('deadline') is-invalid @enderror" id="deadline"
                            name="deadline" value="{{ old('deadline') }}">
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Peserta -->
                <div class="form-group row">
                    <label for="peserta" class="col-sm-3 col-form-label">Peserta Proyek <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="peserta form-control @error('peserta') is-invalid @enderror" name="peserta[]"
                            id="peserta" multiple="multiple">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ collect(old('peserta'))->contains($user->id) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('peserta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tasks (Opsional) -->
                <div class="form-group row">
                    <label for="tasks" class="col-sm-3 col-form-label">Task (Opsional)</label>
                    <div class="col-sm-9">
                        <table class="table table-bordered" id="taskTable">
                            <thead>
                                <tr>
                                    <th>Judul Task</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Tidak ada task default -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="addTask">Tambah Task</button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('projectmanagement.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.1.1/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
    <style>
        /* Menyamakan tinggi dengan input lainnya */
        .select2-container--bootstrap4 .select2-selection {
            min-height: 38px !important;
            padding: 6px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            /* Memastikan teks sejajar secara vertikal */
        }

        /* Menyesuaikan kotak dropdown agar seragam */
        .select2-container--bootstrap4 .select2-selection--multiple {
            min-height: 38px !important;
            padding: 6px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        /* Memastikan tag peserta tidak terlalu besar */
        .select2-container--bootstrap4 .select2-selection__choice {
            font-size: 14px;
            padding: 2px 8px;
            border-radius: 3px;
            margin-top: 4px;
            /* Agar tidak menempel dengan border */
        }

        /* Mengatasi tampilan jika terlalu panjang */
        .select2-container--bootstrap4 .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Select2 Initialization -->
    <script>
        $(document).ready(function() {
            // Fungsi untuk inisialisasi Select2 dengan AJAX
            function initSelect2(selector, placeholder, url) {
                $(selector).select2({
                    theme: 'bootstrap4',
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: url,
                        type: "POST",
                        delay: 250,
                        dataType: 'json',
                        data: function(params) {
                            return {
                                name: params.term, // Mengambil input pencarian
                                "_token": "{{ csrf_token() }}",
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.id,
                                        text: item.name
                                    };
                                })
                            };
                        },
                    },
                });
            }

            // Inisialisasi untuk peserta proyek
            initSelect2('#peserta', 'Pilih peserta proyek', "{{ route('get-users') }}");

            // Inisialisasi untuk Nama PIC
            initSelect2('#nama_pic', 'Pilih Nama PIC', "{{ route('get-users') }}");
        });
    </script>

    <script>
        $('form').submit(function(event) {
            event.preventDefault(); // Mencegah reload form

            let form = $(this);
            let formData = form.serialize();

            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menyimpan proyek ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Simpan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Proyek berhasil disimpan.",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('projectmanagement.index') }}";
                            });
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON?.errors;
                            let errorMessage = "Terjadi kesalahan, silakan coba lagi.";

                            if (errors) {
                                errorMessage = Object.values(errors).map(msg => msg[0]).join(
                                    "\n");
                            }

                            Swal.fire({
                                title: "Gagal!",
                                text: errorMessage,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let taskCount = 1;

            $('#addTask').click(function() {
                let newRow = `
                <tr>
                    <td><input type="text" name="tasks[${taskCount}][title]" class="form-control" placeholder="Judul Task"></td>
                    <td>
                        <select name="tasks[${taskCount}][status]" class="form-control">
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-danger removeTask">Hapus</button></td>
                </tr>`;

                $('#taskTable tbody').append(newRow);
                taskCount++;
            });

            $(document).on('click', '.removeTask', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>

@stop

@extends('adminlte::page')

@section('title', 'Edit Proyek')

@section('content_header')
    <h1>Edit Proyek</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('projectmanagement.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Proyek -->
                <div class="form-group row">
                    <label for="nama_proyek" class="col-sm-3 col-form-label">Nama Proyek <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('nama_proyek') is-invalid @enderror"
                            id="nama_proyek" name="nama_proyek" value="{{ old('nama_proyek', $project->nama_proyek) }}">
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
                            rows="3">{{ old('description', $project->description) }}</textarea>
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
                            <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="in_progress"
                                {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="completed"
                                {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
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
                            name="deadline" value="{{ old('deadline', $project->deadline) }}">
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Peserta (Menggunakan Select2) -->
                <div class="form-group row">
                    <label for="peserta" class="col-sm-3 col-form-label">Peserta</label>
                    <div class="col-sm-9">
                        <select class="form-control peserta @error('peserta') is-invalid @enderror" name="peserta[]"
                            id="peserta" multiple="multiple">
                        </select>

                        @error('peserta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tasks -->
                <div class="form-group row">
                    <label for="tasks" class="col-sm-3 col-form-label">Task</label>
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
                                @foreach ($project->tasks as $index => $task)
                                    <tr>
                                        <td><input type="text" name="tasks[{{ $index }}][title]"
                                                class="form-control" value="{{ $task->title }}"></td>
                                        <td>
                                            <select name="tasks[{{ $index }}][status]" class="form-control">
                                                <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To
                                                    Do</option>
                                                <option value="in_progress"
                                                    {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress
                                                </option>
                                                <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done
                                                </option>
                                            </select>
                                        </td>
                                        <td><button type="button" class="btn btn-danger removeTask">Hapus</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="addTask">Tambah Task</button>
                    </div>
                </div>


                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('projectmanagement.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.1.1/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
    <style>
        .select2-container--bootstrap4 .select2-selection {
            min-height: 38px !important;
            padding: 6px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        .select2-container--bootstrap4 .select2-selection--multiple {
            min-height: 38px !important;
            padding: 6px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        .select2-container--bootstrap4 .select2-selection__choice {
            font-size: 14px;
            padding: 2px 8px;
            border-radius: 3px;
            margin-top: 4px;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            function initSelect2(selector, placeholder, url, preselected = []) {
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
                                name: params.term,
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
                    }
                });

                preselected.forEach(function(user) {
                    let option = new Option(user.text, user.id, true, true);
                    $(selector).append(option).trigger('change');
                });
            }

            let selectedUsers = @json(
                $project->users->map(function ($user) {
                    return ['id' => $user->id, 'text' => $user->name];
                }));

            initSelect2('#peserta', 'Pilih peserta proyek', "{{ route('get-users') }}", selectedUsers);
        });

        // SweetAlert sebelum submit form
        $('form').submit(function(event) {
            event.preventDefault(); // Mencegah form submit langsung

            let form = $(this);
            let formData = form.serialize();

            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menyimpan perubahan?",
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
                                text: "Perubahan proyek berhasil disimpan.",
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

        // Event listener untuk tombol tambah task
        $('#addTask').click(function() {
            let index = $('#taskTable tbody tr').length;
            let newRow = `
        <tr>
            <td><input type="text" name="tasks[${index}][title]" class="form-control"></td>
            <td>
                <select name="tasks[${index}][status]" class="form-control">
                    <option value="todo">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger removeTask">Hapus</button></td>
        </tr>
    `;
            $('#taskTable tbody').append(newRow);
        });

        // Event listener untuk tombol hapus task
        $(document).on('click', '.removeTask', function() {
            $(this).closest('tr').remove();
        });
    </script>
@stop
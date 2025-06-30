@extends('adminlte::page')

@section('title', 'Detail Proyek')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Project: {{ $project->nama_proyek }}</h1>
        <a href="{{ route('projectmanagement.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5><strong>Nama Project:</strong> {{ $project->nama_proyek }}</h5>
            <p><strong>Status:</strong>
                @if ($project->status == 'pending')
                    <span class="badge bg-secondary">Pending</span>
                @elseif($project->status == 'in_progress')
                    <span class="badge bg-warning">In Progress</span>
                @elseif($project->status == 'completed')
                    <span class="badge bg-success">Completed</span>
                @endif
            </p>
            <p><strong>Deadline:</strong>
                {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : 'Belum ditentukan' }}
            </p>
            <p><strong>Deskripsi:</strong> {{ $project->description ?? 'Tidak ada deskripsi' }}</p>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                <i class="fas fa-upload"></i> Upload Hasil
            </button>

            @if ($project->attachment_path || $project->attachment_link)
                <div class="mt-3">
                    <h5><strong>Attachment:</strong></h5>

                    {{-- Attachment File --}}
                    @if ($project->attachment_path)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-file mr-2"></i>
                            <a href="{{ asset('storage/' . $project->attachment_path) }}" target="_blank" class="mr-2">
                                {{ basename($project->attachment_path) }}
                            </a>
                            <form action="{{ route('projectmanagement.deleteAttachment', ['id' => $project->id, 'type' => 'file']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus file ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Attachment Link --}}
                    @if ($project->attachment_link)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-link mr-2"></i>
                            <a href="{{ $project->attachment_link }}" target="_blank" class="mr-2">
                                {{ $project->attachment_link }}
                            </a>
                            <form action="{{ route('projectmanagement.deleteAttachment', ['id' => $project->id, 'type' => 'link']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus link ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            @endif

        </div>
    </div>

    <!-- Modal Upload -->
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
                <form action="{{ route('projectmanagement.upload', $project->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="attachment">File Attachment</label>
                            <input type="file" class="form-control" id="attachment" name="attachment">
                            <small class="form-text text-muted">Supported formats: PDF, JPG, PNG, JPEG (Max: 2MB)</small>
                        </div>

                        <div class="form-group">
                            <label for="attachment_link">Attachment Link</label>
                            <input type="url" class="form-control" id="attachment_link" name="attachment_link"
                                placeholder="https://example.com/document">
                            <small class="form-text text-muted">Optional: Add a link to an external
                                document/resource</small>
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



    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tugas</h5>
            <button id="addTaskRow" class="btn btn-primary btn-sm shadow">
                <i class="fas fa-plus"></i> Tambah Tugas
            </button>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped" id="taskTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->tasks as $index => $task)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="task-title-text">{{ $task->title }}</span>
                                <input type="text" class="form-control form-control-sm task-title-input d-none"
                                    value="{{ $task->title }}">
                            </td>
                            <td>
                                <select class="form-control form-control-sm status-select"
                                    data-task-id="{{ $task->id }}">
                                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-task" data-task-id="{{ $task->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-success btn-sm save-task d-none" data-task-id="{{ $task->id }}">
                                    <i class="fas fa-save"></i>
                                </button>
                                <form action="{{ route('task.destroy', $task->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<script>
    console.log("Halaman Detail Proyek");

    document.getElementById('addTaskRow').addEventListener('click', function() {
        let table = document.getElementById('taskTable').getElementsByTagName('tbody')[0];
        let rowCount = table.rows.length + 1;
        let newRow = table.insertRow();

        newRow.innerHTML = `
            <td>${rowCount}</td>
            <td><input type="text" class="form-control form-control-sm task-title" placeholder="Nama Tugas"></td>
            <td>
                <select class="form-control form-control-sm task-status">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </td>
            <td>
                <button class="btn btn-success btn-sm saveTask">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <button class="btn btn-danger btn-sm deleteRow">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        newRow.querySelector('.deleteRow').addEventListener('click', function() {
            newRow.remove();
        });

        newRow.querySelector('.saveTask').addEventListener('click', function() {
            let title = newRow.querySelector('.task-title').value;
            let status = newRow.querySelector('.task-status').value;

            if (title.trim() === '') {
                alert('Nama tugas tidak boleh kosong!');
                return;
            }

            fetch("{{ route('task.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    project_id: "{{ $project->id }}",
                    title: title,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tugas berhasil ditambahkan!');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan!');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.getAttribute('data-task-id');
            const newStatus = this.value;
            this.disabled = true;

            fetch(`/task/${taskId}/update-status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status berhasil diupdate!');
                } else {
                    alert('Gagal mengupdate status!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });

    document.querySelectorAll('.edit-task').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            row.querySelector('.task-title-text').classList.add('d-none');
            row.querySelector('.task-title-input').classList.remove('d-none');
            this.classList.add('d-none');
            row.querySelector('.save-task').classList.remove('d-none');
        });
    });

    document.querySelectorAll('.save-task').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            const row = this.closest('tr');
            const newTitle = row.querySelector('.task-title-input').value;

            if (newTitle.trim() === '') {
                alert('Nama tugas tidak boleh kosong!');
                return;
            }

            this.disabled = true;

            fetch(`/task/${taskId}/update-title`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ title: newTitle })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.querySelector('.task-title-text').textContent = newTitle;
                    row.querySelector('.task-title-text').classList.remove('d-none');
                    row.querySelector('.task-title-input').classList.add('d-none');
                    this.classList.add('d-none');
                    row.querySelector('.edit-task').classList.remove('d-none');
                    alert('Nama tugas berhasil diupdate!');
                } else {
                    alert('Gagal mengupdate nama tugas!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });

    document.getElementById('attachment').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        const maxSize = 2 * 1024 * 1024;

        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung! Hanya PDF, JPG, JPEG, atau PNG yang diperbolehkan.');
            this.value = '';
            return;
        }

        if (file.size > maxSize) {
            alert('Ukuran file maksimal 2MB!');
            this.value = '';
            return;
        }
    });
</script>
@stop

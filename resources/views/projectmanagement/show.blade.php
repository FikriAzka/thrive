@extends('adminlte::page')

@section('title', 'Detail Proyek')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Proyek: {{ $project->nama_proyek }}</h1>
        <a href="{{ route('projectmanagement.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5><strong>Nama Proyek:</strong> {{ $project->nama_proyek }}</h5>
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
                                    <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>Todo</option>
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
        document.getElementById('addTaskRow').addEventListener('click', function() {
            let table = document.getElementById('taskTable').getElementsByTagName('tbody')[0];
            let rowCount = table.rows.length + 1;
            let newRow = table.insertRow();

            newRow.innerHTML = `
                    <td>${rowCount}</td>
                    <td><input type="text" class="form-control form-control-sm task-title" placeholder="Nama Tugas"></td>
                    <td>
                        <select class="form-control form-control-sm task-status">
                            <option value="todo">Todo</option>
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

            // Hapus baris jika tombol delete ditekan
            newRow.querySelector('.deleteRow').addEventListener('click', function() {
                newRow.remove();
            });

            // Simpan tugas ke database via AJAX
            newRow.querySelector('.saveTask').addEventListener('click', function() {
                let title = newRow.querySelector('.task-title').value;
                let status = newRow.querySelector('.task-status').value;

                if (title.trim() === '') {
                    alert('Nama tugas tidak boleh kosong!');
                    return;
                }

                // Replace the fetch call section with:
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
                            location.reload(); // Reload halaman untuk menampilkan tugas baru
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

                // Show loading state
                this.disabled = true;

                fetch(`/task/${taskId}/update-status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Status berhasil diupdate!');
                        } else {
                            alert('Gagal mengupdate status!');
                            this.value = this.getAttribute('data-original-value');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupdate status!');
                        this.value = this.getAttribute('data-original-value');
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
            });
        });

        document.querySelectorAll('.edit-task').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.getAttribute('data-task-id');
                const row = this.closest('tr');

                // Toggle visibility of text/input and buttons
                row.querySelector('.task-title-text').classList.add('d-none');
                row.querySelector('.task-title-input').classList.remove('d-none');
                this.classList.add('d-none');
                row.querySelector('.save-task').classList.remove('d-none');
            });
        });

        // Add event listeners for save buttons
        document.querySelectorAll('.save-task').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.getAttribute('data-task-id');
                const row = this.closest('tr');
                const newTitle = row.querySelector('.task-title-input').value;

                if (newTitle.trim() === '') {
                    alert('Nama tugas tidak boleh kosong!');
                    return;
                }

                // Disable the save button while processing
                this.disabled = true;

                fetch(`/task/${taskId}/update-title`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            title: newTitle
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the displayed text
                            row.querySelector('.task-title-text').textContent = newTitle;

                            // Toggle visibility back
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
                        alert('Terjadi kesalahan saat mengupdate nama tugas!');
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
            });
        });
    </script>
@stop



@section('js')
    <script>
        console.log("Halaman Detail Proyek");
    </script>
@stop

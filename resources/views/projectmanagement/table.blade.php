<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Project</th>
            <th>Status</th>
            <th>Deadline</th>
            <th>Pekerja</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($projects as $index => $project)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if ($project->tasks->count() > 0)
                        <a href="{{ route('projectmanagement.show', $project->id) }}">
                            {{ $project->nama_proyek }}
                        </a>
                    @else
                        {{ $project->nama_proyek }}
                    @endif
                </td>
                <td>
                    @if ($project->status == 'pending')
                        <span class="badge bg-secondary">Pending</span>
                    @elseif($project->status == 'in_progress')
                        <span class="badge bg-warning">In Progress</span>
                    @elseif($project->status == 'completed')
                        <span class="badge bg-success">Completed</span>
                    @endif
                </td>
                <td>{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : 'Belum ditentukan' }}
                </td>
                <td>
                    @if ($project->users->isEmpty())
                        <p>Belum ada anggota yang ditugaskan untuk proyek ini.</p>
                    @else
                        <ul>
                            @foreach ($project->users as $user)
                                <li>{{ $user->name }} ({{ $user->email }})</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td>
                    <a href="{{ route('projectmanagement.edit', $project->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('projectmanagement.destroy', $project->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

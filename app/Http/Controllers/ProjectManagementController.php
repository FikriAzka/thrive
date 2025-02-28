<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ProjectManagement;




class ProjectManagementController extends Controller
{
    // Menampilkan daftar proyek
    public function index(Request $request)
{
    $query = ProjectManagement::with('users', 'tasks');

    if ($request->has('search') && $request->search != '') {
        $query->where('nama_proyek', 'like', '%' . $request->search . '%');
    }

    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    $projects = $query->get();

    if ($request->ajax()) {
        return view('projectmanagement.table', compact('projects'))->render();
    }

    return view('projectmanagement.index', compact('projects'));
}


    // Menampilkan form tambah proyek
    public function create()
    {
        $users = User::all(); // or User::where('role', '!=', 'admin')->get() if you want to filter
        return view('projectmanagement.create', compact('users'));
    }

    public function store(Request $request)
    {
        //dd($request->all()); // Untuk debug, pastikan data dikirim dengan benar

        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
            'deadline' => 'required|date',
            'peserta' => 'required|array|min:1',
            'peserta.*' => 'exists:users,id',
            'tasks' => 'nullable|array',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.status' => 'required|in:todo,in_progress,done',
            'tasks.*.due_date' => 'nullable|date',
        ]);

        // Simpan proyek
        $project = ProjectManagement::create([
            'nama_proyek' => $request->nama_proyek,
            'description' => $request->description,
            'status' => $request->status,
            'deadline' => $request->deadline,
        ]);

        // Attach peserta ke proyek
        $project->users()->attach($request->peserta);

        // Simpan tasks jika ada
        if ($request->has('tasks')) {
            foreach ($request->tasks as $task) {
                Task::create([
                    'project_management_id' => $project->id, // Hubungkan task ke proyek
                    'title' => $task['title'],
                    'description' => $task['description'] ?? null,
                    'status' => $task['status'],
                    'due_date' => $task['due_date'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('projectmanagement.index')
            ->with('success', 'Proyek dan tugas berhasil dibuat!');
    }


    // Menampilkan detail proyek
    public function show($id)
    {
        $project = ProjectManagement::findOrFail($id);
        return view('projectmanagement.show', compact('project'));
    }

    // Menampilkan form edit proyek
    public function edit($id)
{
    $project = ProjectManagement::with('users', 'tasks')->findOrFail($id);
    $users = User::all(); // Ambil semua pengguna untuk dropdown peserta

    return view('projectmanagement.edit', compact('project', 'users'));
}



    // Mengupdate proyek
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'deadline' => 'nullable|date',
            'peserta' => 'required|array|min:1',
            'peserta.*' => 'exists:users,id',
            'tasks' => 'nullable|array',
            'tasks.*.id' => 'nullable|exists:tasks,id', // Pastikan task yang ada memang valid
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.status' => 'required|in:todo,in_progress,done',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.due_date' => 'nullable|date',
        ]);

        // Temukan proyek
        $project = ProjectManagement::findOrFail($id);

        // Update data proyek
        $project->update([
            'nama_proyek' => $request->nama_proyek,
            'description' => $request->description,
            'status' => $request->status,
            'deadline' => $request->deadline,
        ]);

        // Update peserta proyek
        $project->users()->sync($request->peserta);

        // Update Tasks
        $existingTaskIds = $project->tasks->pluck('id')->toArray();
        $updatedTaskIds = [];

        if ($request->has('tasks')) {
            foreach ($request->tasks as $taskData) {
                if (isset($taskData['id'])) {
                    // Jika task sudah ada, update
                    $task = Task::find($taskData['id']);
                    if ($task) {
                        $task->update([
                            'title' => $taskData['title'],
                            'status' => $taskData['status'],
                            'description' => $taskData['description'] ?? null,
                            'due_date' => $taskData['due_date'] ?? null,
                        ]);
                        $updatedTaskIds[] = $task->id;
                    }
                } else {
                    // Jika task baru, buat task
                    $newTask = Task::create([
                        'project_management_id' => $project->id,
                        'title' => $taskData['title'],
                        'status' => $taskData['status'],
                        'description' => $taskData['description'] ?? null,
                        'due_date' => $taskData['due_date'] ?? null,
                    ]);
                    $updatedTaskIds[] = $newTask->id;
                }
            }
        }

        // Hapus task yang tidak dikirim dari form (dihapus oleh user)
        $tasksToDelete = array_diff($existingTaskIds, $updatedTaskIds);
        Task::whereIn('id', $tasksToDelete)->delete();

        return redirect()->route('projectmanagement.index')
                        ->with('success', 'Proyek dan tugas berhasil diperbarui.');
    }


    // Menghapus proyek
    public function destroy($id)
    {
        $project = ProjectManagement::findOrFail($id);
        $project->delete();

        return redirect()->route('projectmanagement.index')
                         ->with('success', 'Proyek berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectManagement;
use App\Models\Task;

class ProjectManagementController extends Controller
{
    // Menampilkan daftar proyek
    public function index()
    {
        $projects = ProjectManagement::all();
        $tasks = Task::all();
        return view('projectmanagement.index', compact('projects', 'tasks'));
    }

    // Menampilkan form tambah proyek
    public function create()
    {
        return view('projectmanagement.create');
    }

    // Menyimpan proyek baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'deadline' => 'nullable|date',
        ]);

        ProjectManagement::create($request->all());

        return redirect()->route('projectmanagement.index')
                         ->with('success', 'Proyek berhasil ditambahkan.');
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
        $project = ProjectManagement::findOrFail($id);
        return view('projectmanagement.edit', compact('project'));
    }

    // Mengupdate proyek
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'deadline' => 'nullable|date',
        ]);

        $project = ProjectManagement::findOrFail($id);
        $project->update($request->only(['nama_proyek', 'description', 'status', 'deadline']));

        return redirect()->route('projectmanagement.index')
                         ->with('success', 'Proyek berhasil diperbarui.');
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

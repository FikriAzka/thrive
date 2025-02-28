<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\ProjectManagementController;
use App\Models\ProjectManagement;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function edit(Task $task)
    {
        return view('task.edit', compact('task'));
    }

    public function store(Request $request)
{
    $request->validate([
        'project_id' => 'required|exists:project_managements,id', // Changed from project_management to project_managements
        'title' => 'required|string|max:255',
        'status' => 'required|in:todo,in_progress,done',
    ]);

    $project = ProjectManagement::findOrFail($request->project_id);
    
    $task = $project->tasks()->create([
        'title' => $request->title,
        'status' => $request->status,
    ]);

    return response()->json([
        'success' => true,
        'task' => $task
    ]);
}


    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',  // Sesuaikan dengan field di view
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',  // Sesuaikan dengan opsi di view
            'user_id' => 'nullable|exists:users,id'
        ]);

        $task->update($validated);

        return redirect()
            ->route('projectmanagement.show', $task->project_management_id)
            ->with('success', 'Task berhasil diperbarui');
    }

    
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:todo,in_progress,done',
    ]);

    return DB::transaction(function () use ($id, $request) {
        $task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->save();

        // Cek apakah semua task dalam proyek sudah selesai
        $allTasksCompleted = !Task::where('project_management_id', $task->project_management_id)
            ->whereIn('status', ['todo', 'in_progress'])
            ->exists();

        if ($allTasksCompleted) {
            $project = ProjectManagement::find($task->project_management_id);

            if ($project) { // Pastikan proyek ada sebelum update
                $project->status = 'completed';
                $project->save();
            }
        } else {
            // Jika ada tugas yang belum selesai, ubah status proyek menjadi "in_progress"
            $project = ProjectManagement::find($task->project_management_id);

            if ($project && $project->status !== 'in_progress') {
                $project->status = 'in_progress';
                $project->save();
            }
        }

        return response()->json(['success' => true]);
    });
}



    public function updateTitle(Task $task, Request $request)
{
    try {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $task->update([
            'title' => $request->title
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Title updated successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update title'
        ], 500);
    }
}



    public function destroy(Task $task)
    {
        $projectId = $task->project_management_id;
        $task->delete();

        return redirect()
            ->route('projectmanagement.show', $projectId)
            ->with('success', 'Task berhasil dihapus');
    }
}
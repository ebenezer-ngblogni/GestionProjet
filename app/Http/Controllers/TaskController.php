<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TaskController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Project $project)
    {
        $this->authorize('create', [Task::class, $project]);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'due_date' => 'required|date|after:today',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:en_cours,termine,en_attente',
            'files.*' => 'nullable|file|max:10240' // 10MB max
        ]);

        DB::transaction(function () use ($validated, $request, $project) {
            $task = $project->tasks()->create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'assigned_to' => $validated['assigned_to'],
                'status' => $validated['status'],
                'created_by' => auth()->id()
            ]);

            // Gérer les fichiers joints
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('task-files');
                    $task->files()->create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType()
                    ]);
                }
            }

            // Envoyer une notification si la tâche est assignée
            if ($validated['assigned_to']) {
                $user = User::find($validated['assigned_to']);
                $user->notify(new TaskAssigned($task));
            }
        });

        return redirect()->route('projects.show', $project)
            ->with('success', 'Tâche créée avec succès.');
    }

    public function create(Project $project)
    {
        $this->authorize('create', [Task::class, $project]);

        return view('tasks.create', [
            'project' => $project,
            'members' => $project->members,
        ]);
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:en_cours,termine,en_attente',
            'files.*' => 'nullable|file|max:10240'
        ]);

        DB::transaction(function () use ($validated, $request, $task) {
            $oldAssignee = $task->assigned_to;

            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'assigned_to' => $validated['assigned_to'],
                'status' => $validated['status']
            ]);

            // Gérer les nouveaux fichiers
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('task-files');
                    $task->files()->create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType()
                    ]);
                }
            }

            // Notifier le nouvel assigné si changé
            if ($validated['assigned_to'] && $oldAssignee !== $validated['assigned_to']) {
                $user = User::find($validated['assigned_to']);
                $user->notify(new TaskAssigned($task));
            }
        });

        return back()->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);

        DB::transaction(function () use ($task) {
            // Supprimer les fichiers associés
            foreach ($task->files as $file) {
                Storage::delete($file->path);
                $file->delete();
            }
            $task->delete();
        });

        return back()->with('success', 'Tâche supprimée avec succès.');
    }

    public function complete(Task $task)
    {
        $this->authorize('complete', $task);

        $task->update([
            'status' => 'termine',
            'completed_at' => now()
        ]);


        return back()->with('success', 'Tâche marquée comme terminée.');
    }

    public function assign(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $task->update(['assigned_to' => $validated['user_id']]);

        // Notifier le nouvel assigné
        $user = User::find($validated['user_id']);
        $user->notify(new TaskAssigned($task));

        return back()->with('success', 'Tâche assignée avec succès.');
    }
}

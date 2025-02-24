<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectInvitation;
use App\Notifications\ProjectStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $projects = auth()->user()->projects()
            ->withCount(['tasks', 'completedTasks'])
            ->with(['owner', 'members'])
            ->latest()
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        if (auth()->check()) {
            $this->authorize('create', Project::class);
            return view('projects.create');
        } else {
            return redirect()->route('login');
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:en_attente,en_cours,termine'
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                $project = Project::create([
                    ...$validated,
                    'owner_id' => auth()->id()
                ]);

                $project->members()->attach(auth()->id(), ['role' => 'admin']);
            });

            return redirect()->route('dashboard')
                ->with('success', 'Projet créé avec succès.');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                dd($e->getMessage());
            }

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du projet.');
        }
    }

    public function show(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        // Récupérer le filtre depuis l'URL, 'all' par défaut
        $filter = $request->query('filter', 'all');

        // Préparer la relation tasks avec le filtre
        $tasksRelation = function ($query) use ($filter) {
            switch ($filter) {
                case 'pending':
                    $query->where('status', 'en_cours');
                    break;
                case 'completed':
                    $query->where('status', 'termine');
                    break;
            }
        };

        // Charger les relations avec le filtre appliqué
        $project->load([
            'tasks' => $tasksRelation,
            'tasks.assignedTo',
            'tasks.files',
            'members',
            'activities' => fn($query) => $query->latest()->take(10)
        ]);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:en_attente,en_cours,termine'
        ]);

        $oldStatus = $project->status;
        $project->update($validated);

        if ($oldStatus !== $project->status) {
            foreach ($project->members as $member) {
                $member->notify(new ProjectStatusUpdated($project));
            }
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        DB::transaction(function () use ($project) {
            foreach ($project->tasks as $task) {
                foreach ($task->files as $file) {
                    Storage::delete($file->path);
                    $file->delete();
                }
            }
            $project->delete();
        });

        return redirect()->route('dashboard')
            ->with('success', 'Projet supprimé avec succès.');
    }

    public function invite(Request $request, Project $project)
    {
        $this->authorize('invite', $project);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:member,admin'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($project->members->contains($user)) {
            return back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }

        $project->members()->attach($user, ['role' => $validated['role']]);
        $user->notify(new ProjectInvitation($project));

        return back()->with('success', 'Invitation envoyée avec succès.');
    }

    public function members(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['members', 'owner']);

        return view('projects.members', [
            'project' => $project,
            'members' => $project->members,
            'owner' => $project->owner
        ]);
    }

    public function updateStatus(Request $request, Project $project)
    {
        $this->authorize('updateStatus', $project);

        $validated = $request->validate([
            'status' => 'required|in:en_attente,en_cours,termine'
        ]);

        $project->update($validated);

        foreach ($project->members as $member) {
            $member->notify(new ProjectStatusUpdated($project));
        }

        return back()->with('success', 'Statut du projet mis à jour.');
    }

    public function removeMember(Project $project, User $user)
    {
        $this->authorize('invite', $project);

        if ($user->id === $project->owner_id) {
            return back()->with('error', 'Impossible de retirer le propriétaire du projet.');
        }

        if (!$project->members->contains($user)) {
            return back()->with('error', 'Cet utilisateur n\'est pas membre du projet.');
        }

        $project->members()->detach($user->id);

        return back()->with('success', 'Membre retiré avec succès.');
    }
}

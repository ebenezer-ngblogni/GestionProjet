<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        // Statistiques générales
        $stats = [
            'activeProjects' => $user->projects()
                ->where('status', 'en_cours')
                ->count(),

            'pendingTasks' => Task::whereHas('project', function ($query) use ($user) {
                $query->whereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->where('status', 'en_cours')
            ->where('assigned_to', $user->id)
            ->count(),

            'dueSoonTasks' => Task::whereHas('project', function ($query) use ($user) {
                $query->whereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->where('status', 'en_cours')
            ->where('due_date', '<=', Carbon::now()->addDays(7))
            ->count(),

            'completedTasksThisWeek' => Task::whereHas('project', function ($query) use ($user) {
                $query->whereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->where('status', 'terminee')
            ->where('completed_at', '>=', Carbon::now()->subWeek())
            ->count()
        ];

        // Projets récents avec leurs tâches
        $projects = $user->projects()
            ->with(['tasks' => function ($query) {
                $query->latest()->take(5);
            }, 'members'])
            ->withCount(['tasks', 'completedTasks'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($project) {
                $project->progress = $project->tasks_count > 0
                    ? round(($project->completed_tasks_count / $project->tasks_count) * 100)
                    : 0;
                return $project;
            });

        // Tâches assignées à l'utilisateur
        $assignedTasks = Task::with('project')
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'terminee')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Activités récentes
        $activities = Activity::whereHasMorph(
            'subject',
            [Project::class, Task::class],
            function ($query) use ($user) {
                $query->where(function($q) use ($user) {
                    // Pour les projets
                    $q->when($q->getModel() instanceof Project, function($query) use ($user) {
                        $query->whereHas('members', function($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                    })
                    // Pour les tâches
                    ->when($q->getModel() instanceof Task, function($query) use ($user) {
                        $query->whereHas('project.members', function($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                    });
                });
            }
        )
        ->with('causer', 'subject')
        ->latest()
        ->take(10)
        ->get();

        return view('dashboard', compact(
            'stats',
            'projects',
            'assignedTasks',
            'activities'
        ));
    }

    public function getProjectStats(Project $project)
    {
        $this->authorize('view', $project);

        $stats = [
            'totalTasks' => $project->tasks()->count(),
            'completedTasks' => $project->tasks()->where('status', 'terminee')->count(),
            'pendingTasks' => $project->tasks()->where('status', 'en_cours')->count(),
            'membersCount' => $project->members()->count(),
            'filesCount' => $project->tasks()->withCount('files')->get()->sum('files_count'),
            'daysRemaining' => Carbon::now()->diffInDays($project->end_date, false)
        ];

        // Progression par semaine
        $weeklyProgress = $project->tasks()
            ->where('status', 'terminee')
            ->groupBy('completed_at')
            ->selectRaw('DATE(completed_at) as date, count(*) as completed')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('W');
            });

        return response()->json([
            'stats' => $stats,
            'weeklyProgress' => $weeklyProgress
        ]);
    }
}

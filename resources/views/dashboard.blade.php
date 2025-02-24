<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- En-tête du tableau de bord avec le nouveau bouton -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Tableau de bord</h2>
                    <p class="mt-1 text-gray-600">Bienvenue, {{ auth()->user()->name }}</p>
                </div>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau projet
                </a>
            </div>

            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">Projets actifs</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['activeProjects'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">Tâches en cours</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['pendingTasks'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">Tâches à échéance</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['dueSoonTasks'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">Terminées cette semaine</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['completedTasksThisWeek'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Projets récents -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-semibold">Projets récents</h3>
                            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                                Voir tous les projets
                            </a>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @forelse ($projects as $project)
                            <div class="p-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('projects.show', $project) }}" class="text-lg font-medium text-indigo-600 hover:text-indigo-800">
                                            {{ $project->title }}
                                        </a>
                                        <div class="mt-1">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $project->status === 'en_cours' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $project->status === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $project->status === 'termine' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $project->tasks_count }} tâches
                                    </div>
                                </div>

                                <!-- Barre de progression -->
                                <div class="mt-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $project->progress }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-1 text-sm text-gray-600">
                                        <span>{{ $project->completed_tasks_count }} / {{ $project->tasks_count }} tâches</span>
                                        <span>{{ $project->progress }}%</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500">
                                Aucun projet récent.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tâches assignées -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold">Tâches assignées</h3>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @forelse ($assignedTasks as $task)
                            <div class="p-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="text-lg font-medium text-indigo-600 hover:text-indigo-800">
                                            {{ $task->title }}
                                        </a>
                                        <p class="text-sm text-gray-500">{{ $task->project->title }}</p>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-500">Échéance :</span>
                                        <span class="ml-1 {{ Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500">
                                Aucune tâche assignée.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Activités récentes -->
            <div class="mt-8 bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold">Activités récentes</h3>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse ($activities as $activity)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        {{ substr($activity->causer->name ?? 'Système', 0, 2) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $activity->causer->name ?? 'Système' }}</span>
                                        {{ $activity->description }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            Aucune activité récente.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

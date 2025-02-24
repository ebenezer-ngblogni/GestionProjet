<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- En-tête du projet -->
            <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                                <p class="text-gray-600 max-w-2xl">{{ $project->description }}</p>
                            </div>

                            <div class="flex items-center space-x-6">
                                <span class="flex items-center text-gray-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $project->start_date->format('d/m/Y') }} -
                                    {{ $project->end_date->format('d/m/Y') }}
                                </span>
                                <span class="px-4 py-1.5 rounded-full text-sm font-medium {{ $project->status_color }}">
                                    {{ $project->status }}
                                </span>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier
                                </a>
                            @endcan
                            @can('delete', $project)
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-50 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total des tâches</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $project->tasks->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tâches terminées</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $project->tasks->where('status', 'termine')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tâches en cours</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $project->tasks->where('status', 'en_cours')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-50 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tâches en retard</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $project->tasks->where('status', '!=', 'termine')->where('due_date', '<', now())->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membres du projet -->
            <div class="bg-white rounded-xl shadow-sm mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-900">Membres du projet</h2>
                        </div>
                        @can('invite', $project)
                            <a href="{{ route('projects.members', $project) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Inviter un membre
                            </a>
                        @endcan
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($project->members as $member)
                            <div
                                class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                                <img class="h-10 w-10 rounded-full object-cover"
                                    src="{{ $member->profile_photo_url }}" alt="{{ $member->name }}">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $member->pivot->role }}</p>
                                </div>
                                <button
                                    class="ml-auto text-gray-400 hover:text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Section des tâches -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Tâches</h2>
                        @can('invite', $project)
                            <a href="{{ route('projects.tasks.create', $project) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Nouvelle tâche
                            </a>
                        @endcan
                    </div>

                    <div class="flex space-x-2 mb-6">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->query('filter', 'all') === 'all' ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Toutes
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'pending']) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->query('filter') === 'pending' ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            En cours
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'completed']) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->query('filter') === 'completed' ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Terminées
                        </a>
                    </div>

                    <!-- Liste des tâches -->
                    <div class="space-y-3">
                        @forelse($project->tasks as $task)
                            <div
                                class="group flex items-center justify-between p-4 bg-white border rounded-lg hover:shadow-sm transition-all">
                                <div class="flex items-center space-x-4">
                                    @can('complete', $task)
                                        <form action="{{ route('tasks.complete', $task) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <input type="checkbox"
                                                class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500"
                                                @if ($task->status === 'termine') checked @endif
                                                onchange="this.form.submit()">
                                        </form>
                                    @else
                                        <!-- Option: Afficher une case à cocher désactivée si l'utilisateur n'a pas les droits -->
                                        <input type="checkbox"
                                            class="w-4 h-4 text-gray-300 rounded border-gray-300 cursor-not-allowed"
                                            @if ($task->status === 'termine') checked @endif disabled>
                                    @endcan
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-medium {{ $task->status === 'termine' ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                            {{ $task->title }}
                                        </span>
                                        @if ($task->description)
                                            <p class="text-sm text-gray-500 mt-0.5">
                                                {{ Str::limit($task->description, 100) }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $task->due_date->format('d/m/Y') }}
                                    </span>

                                    @can('update', $task)
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-gray-600 transition-opacity">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Modifier
                                                </a>
                                                <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune tâche</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle tâche pour ce
                                    projet.</p>
                                @can('create', [App\Models\Task::class, $project])
                                    <div class="mt-6">
                                        <a href="{{ route('projects.tasks.create', $project) }}"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Créer une tâche
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

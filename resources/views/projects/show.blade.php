<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- En-tête du projet -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h1>
                            <p class="mt-2 text-gray-600">{{ $project->description }}</p>
                            <div class="mt-4 flex items-center space-x-4">
                                <span class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $project->start_date->format('d/m/Y') }} -
                                    {{ $project->end_date->format('d/m/Y') }}
                                </span>
                                <span class="px-3 py-1 text-sm rounded-full {{ $project->status_color }}">
                                    {{ $project->status }}
                                </span>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}"
                                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">
                                    Modifier
                                </a>
                            @endcan
                            @can('delete', $project)
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-100 text-red-700 px-4 py-2 rounded-md hover:bg-red-200"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                        Supprimer
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques du projet -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Total des tâches</h3>
                            <p class="mt-1 text-lg text-gray-500">{{ $project->tasks->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Tâches terminées</h3>
                            <p class="mt-1 text-lg text-gray-500">
                                {{ $project->tasks->where('status', 'terminee')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Tâches en cours</h3>
                            <p class="mt-1 text-lg text-gray-500">
                                {{ $project->tasks->where('status', 'en_cours')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Tâches en retard</h3>
                            <p class="mt-1 text-lg text-gray-500">
                                {{ $project->tasks->where('status', '!=', 'terminee')->where('due_date', '<', now())->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Membres du projet -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Membres du projet</h2>
                        @can('invite', $project)
                            <a href="{{ route('projects.members', $project) }}"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Inviter un membre
                            </a>
                        @endcan
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($project->members as $member)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <img class="h-10 w-10 rounded-full" src="{{ $member->profile_photo_url }}"
                                    alt="{{ $member->name }}">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $member->pivot->role }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold mb-4 sm:mb-0">Tâches</h2>
                        <div class="flex items-center space-x-4">
                            @can('invite', $project)
                                <a href="{{ route('projects.tasks.create', $project) }}"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                    Créer une tâche
                                </a>
                            @endcan
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="flex space-x-4 mb-6">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}"
                            class="px-4 py-2 rounded-md {{ request('filter', 'all') === 'all' ? 'bg-gray-200' : 'bg-gray-100' }}">
                            Toutes
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'pending']) }}"
                            class="px-4 py-2 rounded-md {{ request('filter') === 'pending' ? 'bg-gray-200' : 'bg-gray-100' }}">
                            En cours
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'completed']) }}"
                            class="px-4 py-2 rounded-md {{ request('filter') === 'completed' ? 'bg-gray-200' : 'bg-gray-100' }}">
                            Terminées
                        </a>
                    </div>

                    <!-- Liste des tâches -->
                    <div class="space-y-4">
                        @forelse($project->tasks as $task)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="border rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <form action="{{ route('tasks.complete', $task) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <input type="checkbox" class="h-4 w-4 text-indigo-600"
                                                    @if ($task->status === 'terminee') checked @endif
                                                    onchange="this.form.submit()">
                                            </form>
                                            <span
                                                class="ml-3 text-lg font-medium @if ($task->status === 'terminee') line-through text-gray-400 @endif">
                                                {{ $task->title }}
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-sm text-gray-500">
                                                Échéance : {{ $task->due_date->format('d/m/Y') }}
                                            </span>
                                            @can('update', $task)
                                                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                                                    class="text-gray-400 hover:text-gray-500">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                        </div>
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
                                    <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle tâche pour
                                        ce
                                        projet.</p>
                                    @can('create', [App\Models\Task::class, $project])
                                        <div class="mt-6">
                                            <a href="{{ route('projects.tasks.create', $project) }}"
                                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
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

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Mes Projets</h2>
                @can('create', App\Models\Project::class)
                    <a href="{{ route('projects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Nouveau Projet
                    </a>
                @endcan
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($projects->isEmpty())
                    <div class="p-6 text-gray-500 text-center">
                        Aucun projet n'a été créé pour le moment.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Titre
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date de fin
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Progrès
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Membres
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($projects as $project)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600">
                                                        {{ $project->title }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $project->status === 'en cours' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $project->status === 'en attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $project->status === 'terminé' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $project->end_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $project->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $project->progress }}%</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex -space-x-2">
                                                @foreach($project->members->take(3) as $member)
                                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs">
                                                        {{ substr($member->name, 0, 2) }}
                                                    </div>
                                                @endforeach
                                                @if($project->members->count() > 3)
                                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                                                        +{{ $project->members->count() - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-900">
                                                    Voir
                                                </a>
                                                @can('update', $project)
                                                    <a href="{{ route('projects.edit', $project) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        Modifier
                                                    </a>
                                                @endcan
                                                @can('delete', $project)
                                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if($projects->hasPages())
                <div class="mt-4">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

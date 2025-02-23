<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- En-tête -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Membres du projet : {{ $project->title }}
                        </h2>
                        <a href="{{ route('projects.show', $project) }}"
                           class="text-blue-500 hover:text-blue-700">
                            Retour au projet
                        </a>
                    </div>

                    <!-- Formulaire d'invitation -->
                    @can('invite', $project)
                        <div class="bg-gray-50 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-medium mb-4">Inviter un nouveau membre</h3>

                            <form action="{{ route('projects.invite', $project) }}" method="POST"
                                  class="space-y-4 md:space-y-0 md:flex md:gap-4 items-end">
                                @csrf

                                <div class="flex-1">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email du membre
                                    </label>
                                    <input type="email" name="email" id="email" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="w-full md:w-48">
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                        Rôle
                                    </label>
                                    <select name="role" id="role" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="member">Membre</option>
                                        <option value="admin">Administrateur</option>
                                    </select>
                                </div>

                                <button type="submit"
                                        class="w-full md:w-auto px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Inviter
                                </button>
                            </form>
                        </div>
                    @endcan

                    <!-- Liste des membres -->
                    <div class="bg-white shadow overflow-hidden rounded-md">
                        <ul role="list" class="divide-y divide-gray-200">
                            <!-- Propriétaire -->
                            <li class="p-4 flex items-center justify-between bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-500">
                                            <span class="text-lg font-medium leading-none text-white">
                                                {{ substr($project->owner->name, 0, 1) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $project->owner->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $project->owner->email }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Propriétaire
                                </span>
                            </li>

                            <!-- Autres membres -->
                            @foreach($members as $member)
                                @if($member->id !== $project->owner_id)
                                    <li class="p-4 flex items-center justify-between hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-500">
                                                    <span class="text-lg font-medium leading-none text-white">
                                                        {{ substr($member->name, 0, 1) }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $member->pivot->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $member->pivot->role === 'admin' ? 'Administrateur' : 'Membre' }}
                                            </span>

                                            @can('invite', $project)
                                                <form action="{{ route('projects.members.remove', [$project, $member]) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir retirer ce membre ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-500 hover:text-red-700 text-sm font-medium">
                                                        Retirer
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <!-- Messages de feedback -->
                    @if(session('success'))
                        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

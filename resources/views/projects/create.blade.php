<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-transform hover:scale-[1.01]">
                <!-- Header with gradient -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8">
                    <h2 class="text-3xl font-bold text-white">Créer un nouveau projet</h2>
                    <p class="mt-2 text-indigo-100">Remplissez les informations ci-dessous pour créer votre projet</p>
                </div>

                <!-- Form Container -->
                <div class="p-8">
                    <form action="{{ route('projects.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Titre -->
                        <div class="space-y-2">
                            <label for="title" class="text-sm font-medium text-gray-900 block">Titre du projet</label>
                            <input type="text" name="title" id="title"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors"
                                placeholder="Entrez le titre du projet"
                                required>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label for="description" class="text-sm font-medium text-gray-900 block">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors resize-none"
                                placeholder="Décrivez votre projet en quelques lignes"></textarea>
                        </div>

                        <!-- Dates Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date de début -->
                            <div class="space-y-2">
                                <label for="start_date" class="text-sm font-medium text-gray-900 block">Date de début</label>
                                <div class="relative">
                                    <input type="date" name="start_date" id="start_date"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors"
                                        required>
                                </div>
                            </div>

                            <!-- Date de fin -->
                            <div class="space-y-2">
                                <label for="end_date" class="text-sm font-medium text-gray-900 block">Date de fin</label>
                                <div class="relative">
                                    <input type="date" name="end_date" id="end_date"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="space-y-2">
                            <label for="status" class="text-sm font-medium text-gray-900 block">Statut</label>
                            <select name="status" id="status"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors bg-white">
                                <option value="en_attente">En attente</option>
                                <option value="en_cours">En cours</option>
                                <option value="termine">Terminé</option>
                            </select>
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="bg-red-50 text-red-500 p-6 rounded-lg border border-red-100">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6">
                            <a href="{{ route('dashboard') }}"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                Annuler
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 transform transition-all active:scale-[0.98] shadow-lg hover:shadow-xl">
                                Créer le projet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

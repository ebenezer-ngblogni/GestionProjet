<x-app-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-50 to-blue-100 p-4">
        <div class="w-full max-w-md transform transition-all">
            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-xl p-8 space-y-8 transition-transform hover:scale-[1.01]">
                <!-- Header -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Inscription</h2>
                    <p class="mt-2 text-sm text-gray-600">Créez votre compte pour commencer</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Nom Field -->
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium text-gray-900 block">Nom</label>
                        <div class="relative">
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors text-gray-800 placeholder-gray-400"
                                placeholder="Votre nom complet"
                            >
                        </div>
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-gray-900 block">Email</label>
                        <div class="relative">
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors text-gray-800 placeholder-gray-400"
                                placeholder="Votre adresse mail"
                            >
                        </div>
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium text-gray-900 block">Mot de passe</label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors text-gray-800"
                                placeholder="Mot de passe"
                            >
                        </div>
                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-medium text-gray-900 block">Confirmer le mot de passe</label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-colors text-gray-800"
                                placeholder="Confirmation mot de passe"
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 transform transition-all active:scale-[0.98] shadow-lg hover:shadow-xl"
                    >
                        Créer mon compte
                    </button>

                    <!-- Login Link -->
                    <div class="text-center">
                        <a
                            href="{{ route('login') }}"
                            class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors"
                        >
                            Déjà inscrit ? Connectez-vous
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

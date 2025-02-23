<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('projects')
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'user'])]
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']  // Maintenant on assigne directement le rôle
        ]);

        return back()->with('success', 'Utilisateur créé avec succès.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => $request->filled('password') ?
                ['required', 'confirmed', Password::defaults()] :
                ['nullable']
        ]);

        if ($user->role === 'admin' &&
            $validated['role'] !== 'admin' &&
            User::where('role', 'admin')->count() === 1) {
            return back()->with('error', 'Impossible de modifier le rôle du dernier administrateur.');
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role']
        ];

        if (isset($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return back()->with('error', 'Impossible de supprimer le dernier administrateur.');
        }

        if ($user->projects()->where('status', 'en_cours')->exists()) {
            return back()->with('error', 'Impossible de supprimer un utilisateur avec des projets actifs.');
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])]
        ]);

        if ($user->role === 'admin' &&
            $validated['role'] !== 'admin' &&
            User::where('role', 'admin')->count() === 1) {
            return back()->with('error', 'Impossible de modifier le rôle du dernier administrateur.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Rôle mis à jour avec succès.');
    }
}

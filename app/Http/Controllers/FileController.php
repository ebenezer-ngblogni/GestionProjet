<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class FileController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string|max:255'
        ]);

        $file = $request->file('file');
        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '_' . time()
            . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('task-files/' . $task->id, $fileName);

        $task->files()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
            'description' => $request->description,
            'uploaded_by' => auth()->id()
        ]);

        return back()->with('success', 'Fichier téléversé avec succès.');
    }

    public function download(File $file)
    {
        $this->authorize('view', $file);

        if (!Storage::exists($file->path)) {
            return back()->with('error', 'Fichier non trouvé.');
        }

        return Storage::download(
            $file->path,
            $file->name,
            ['Content-Type' => $file->type]
        );
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        Storage::delete($file->path);
        $file->delete();

        return back()->with('success', 'Fichier supprimé avec succès.');
    }

    public function update(Request $request, File $file)
    {
        $this->authorize('update', $file);

        $validated = $request->validate([
            'description' => 'nullable|string|max:255'
        ]);

        $file->update($validated);

        return back()->with('success', 'Informations du fichier mises à jour.');
    }
}

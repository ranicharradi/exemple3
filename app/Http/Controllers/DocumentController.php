<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'video_url' => ['required', 'url:http,https', 'max:2048'],
            'title' => ['nullable', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $course->update([
            'video_url' => $validated['video_url'],
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('documents', 'local');

        $course->documents()->create([
            'title' => $validated['title'] ?? pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME),
            'file_path' => $path,
        ]);

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('status', 'Document PDF ajouté avec succès.');
    }
}

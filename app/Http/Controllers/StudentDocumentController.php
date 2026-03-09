<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Document;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentDocumentController extends Controller
{
    public function download(Request $request, Course $course, Document $document): StreamedResponse
    {
        abort_unless($document->course_id === $course->id, 404);

        $hasAccess = Enrollment::query()
            ->where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->where('access_granted', true)
            ->exists();

        abort_unless($hasAccess, 403);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        $downloadName = str()->slug($document->title).'.pdf';

        return Storage::disk('local')->download($document->file_path, $downloadName);
    }
}

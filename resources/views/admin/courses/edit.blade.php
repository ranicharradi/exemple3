<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="kicker">Admin CRUD</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Edit course</h2>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="btn-ghost">Back to courses</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.72fr_0.28fr] lg:px-8">
            <div class="space-y-6">
                <div class="panel border-amber-200 bg-gradient-to-br from-white to-amber-50">
                    <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        @include('admin.courses.partials.form', ['course' => $course, 'submitLabel' => 'Save changes'])
                    </form>
                </div>

                <div class="panel">
                    <p class="kicker">Documents PDF</p>
                    <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Ajouter une ressource</h3>

                    <form method="POST" action="{{ route('admin.courses.documents.store', $course) }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="document_video_url" :value="__('Video URL')" />
                            <x-text-input
                                id="document_video_url"
                                name="video_url"
                                type="url"
                                class="mt-2 block w-full rounded-2xl border-slate-300"
                                :value="old('video_url', $course->video_url)"
                                required
                            />
                            <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="document_title" :value="__('Titre du document (optionnel)')" />
                            <x-text-input
                                id="document_title"
                                name="title"
                                type="text"
                                class="mt-2 block w-full rounded-2xl border-slate-300"
                                :value="old('title')"
                                placeholder="Ex: Support chapitre 1"
                            />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="document_file" :value="__('Fichier PDF (max 10Mo)')" />
                            <input
                                id="document_file"
                                name="file"
                                type="file"
                                accept="application/pdf,.pdf"
                                required
                                class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-700"
                            />
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <button type="submit" class="btn-admin-entry">
                            Uploader le PDF
                        </button>
                    </form>

                    @if ($course->documents->isNotEmpty())
                        <div class="mt-6 space-y-3">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-400">Déjà ajoutés</p>
                            @foreach ($course->documents as $document)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                    {{ $document->title }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <aside class="panel space-y-4">
                <p class="kicker">Live record</p>
                <div class="space-y-3 text-sm leading-7 text-slate-600">
                    <p>Any change here updates the public course detail page immediately.</p>
                    <p>Use the course list to see whether payments or enrollments already exist.</p>
                    <p>If the course has active references, deletion is blocked for safety.</p>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>

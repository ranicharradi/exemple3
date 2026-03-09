<div>
    <x-input-label for="title" :value="__('Title')" />
    <x-text-input id="title" name="title" type="text" class="mt-2 block w-full rounded-2xl border-slate-300" :value="old('title', $course->title)" required />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div>
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="7" class="mt-2 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-amber-400 focus:ring-amber-400" required>{{ old('description', $course->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <x-input-label for="price" :value="__('Price')" />
        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-2 block w-full rounded-2xl border-slate-300" :value="old('price', $course->price)" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="video_url" :value="__('Video URL')" />
        <x-text-input id="video_url" name="video_url" type="url" class="mt-2 block w-full rounded-2xl border-slate-300" :value="old('video_url', $course->video_url)" required />
        <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
    </div>
</div>

<div class="flex flex-wrap items-center gap-3">
    <button type="submit" class="btn-admin-entry">
        {{ $submitLabel ?? 'Save course' }}
    </button>
    <a href="{{ route('admin.courses.index') }}" class="btn-neutral">Cancel</a>
</div>

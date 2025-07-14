<form wire:submit.prevent="submit" enctype="multipart/form-data" class="bg-white shadow rounded-xl p-4 mb-4 flex items-start gap-3">
    <x-filament-panels::avatar.user :user="filament()->auth()->user()" size="lg" />
    <div class="flex-1">
        <textarea wire:model.defer="form.description" rows="2" class="w-full border-0 focus:ring-0 text-gray-800 placeholder-gray-400 resize-none" placeholder="What's Happening?" required></textarea>
        @error('form.description') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
        <div class="flex items-center justify-between mt-2">
            <div class="flex gap-2 items-center">
                <label class="p-2 rounded hover:bg-gray-100 cursor-pointer" title="Add image">
                    <input type="file" wire:model="form.images" multiple accept="image/*" class="hidden" />
                    <x-filament::icon icon="heroicon-m-photo" class="h-5 w-5 text-gray-500" />
                </label>
                <div class="flex gap-1 ml-2">
                    @foreach ($form->images as $image)
                        <img src="{{ $image->temporaryUrl() }}" class="w-10 h-10 object-cover rounded" />
                    @endforeach
                </div>
            </div>
            <button type="submit" class="text-black px-4 hover:opacity-90 py-2 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 font-semibold shadow hover:from-indigo-600 hover:to-purple-600 transition">
                Create Post
            </button>
        </div>
        @error('images.*') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
    </div>
</form>

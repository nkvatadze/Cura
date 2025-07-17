<x-filament-panels::page>
    <livewire:create-post />

    @foreach ($this->posts as $post)
        <div class="bg-white rounded-2xl shadow p-6 mb-6 flex gap-4 items-start" wire:key="{{ $post->id}}">
            <x-filament-panels::avatar.user :user="$post->user" size="xl" class="mt-1" />
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-semibold text-gray-900 truncate">{{ $post->user->name }}</span>
                    <span class="text-xs text-gray-500">· {{ $post->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-gray-800 text-base mb-3 break-words">{{ $post->description }}</div>
                @if ($post->getMedia()->isNotEmpty())
                    <div class="flex gap-2 mt-2">
                        @foreach ($post->getMedia() as $media)
                            <img src="{{ $media->getUrl() }}" class="w-28 h-28 object-cover rounded-xl border border-gray-200" />
                        @endforeach
                    </div>
                @endif
                <div class="flex gap-4 mt-4">
                    <button class="flex items-center gap-1 text-gray-500 hover:text-indigo-600 font-medium text-sm focus:outline-none">
                        <x-filament::icon icon="heroicon-m-hand-thumb-up" class="w-5 h-5" />
                        <span>{{ $post->userReactions()->count() }}</span>
                        <span>Like</span>
                    </button>
                    <button class="flex items-center gap-1 text-gray-500 hover:text-indigo-600 font-medium text-sm focus:outline-none">
                        <x-filament::icon icon="heroicon-m-chat-bubble-left-ellipsis" class="w-5 h-5" />
                        <span>{{ $post->comments()->count() }}</span>
                        <span>Comment</span>
                    </button>
                </div>
            </div>
        </div>
    @endforeach

</x-filament-panels::page>

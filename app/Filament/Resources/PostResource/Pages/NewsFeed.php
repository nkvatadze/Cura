<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class NewsFeed extends Page
{
    protected static string $resource = PostResource::class;

    protected static string $view = 'filament.resources.post-resource.pages.news-feed';

    protected static ?string $title = 'News Feed';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public Collection $posts;

    public $listeners = [
        'postCreated' => 'fetchPosts',
    ];

    public function mount()
    {
        $this->fetchPosts();
    }

    public function fetchPosts()
    {
        $this->posts = Post::with('user')->latest()->get();
    }
}

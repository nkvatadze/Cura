<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class NewsFeed extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.news-feed';

    protected static ?string $navigationLabel = 'News Feed';

    protected static ?string $title = 'News Feed';

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

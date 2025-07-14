<?php

namespace App\Livewire\Forms;

use App\Models\Post;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PostForm extends Form
{
    #[Validate('required|min:1')]
    public string $description = '';

    #[Validate([
        'images' => 'nullable',
        'images.*' => [
            'image',
            'max:1024',
        ],
    ])]
    public array $images = [];

    public function create()
    {
        $this->validate();
        $post = Post::create(
            $this->all()
        );
        foreach ($this->images as $image) {
            $post->addMedia($image->getRealPath())
                ->usingFileName($image->getClientOriginalName())
                ->toMediaCollection();
        }
        $this->reset(['description', 'images']);
    }
}

<?php

namespace App\Livewire;

use App\Livewire\Forms\PostForm;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    public PostForm $form;

    public function submit()
    {
        $this->form->create();

        $this->dispatch('postCreated');

        Notification::make()
            ->title('Post created successfully')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}

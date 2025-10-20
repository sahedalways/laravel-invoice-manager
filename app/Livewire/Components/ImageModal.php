<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ImageModal extends Component
{
    public $imageUrl;

    protected $listeners = [];

    public function render()
    {
        return view('livewire.components.image-modal');
    }
}

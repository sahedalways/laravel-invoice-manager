<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class ImageModal extends Component
{
    public $imageUrl;

    protected $listeners = [];

    public function render()
    {
        return view('livewire.admin.components.image-modal');
    }
}

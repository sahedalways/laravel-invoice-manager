<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SideBar extends Component
{

    public function render()
    {
        return view('livewire.admin.components.side-bar');
    }


    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}

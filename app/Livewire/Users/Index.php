<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class Index extends Component
{
    public function render()
    {
        return view('livewire.users.index', ['users' => User::all()]);
    }
}

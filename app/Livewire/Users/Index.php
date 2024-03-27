<?php

namespace App\Livewire\Users;

use App\Models\Conversation;
use Livewire\Component;
use App\Models\User;

class Index extends Component
{
    public function message(int $userId)
    {
        $conversation = auth()->user()->findOrCreateConversationWith($userId);

        return redirect(route('chats.show', ['chat' => $conversation]));
    }
    public function render()
    {
        return view('livewire.users.index', ['users' => User::except([auth()->id()])->get()]);
    }
}

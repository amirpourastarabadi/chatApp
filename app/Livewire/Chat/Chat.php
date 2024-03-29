<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;

class Chat extends Component
{
    public $chat;
    public $selectedConversation;

    public function mount()
    {
        $this->selectedConversation = Conversation::findOrFail($this->chat);
        auth()->user()->markMessagesAsReadeFrom($this->selectedConversation);
    }

    public function render()
    {
        return view('livewire.chat.chat');
    }
}

<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;
    public $lastMessage;

    public function loadMessages()
    {
        $this->loadedMessages = $this->selectedConversation->messages;
    }

    public function sendMessage()
    {
        $message = $this->selectedConversation->cereateMessage(
            body: $this->body,
            sender: auth()->id(),
            receiver: $this->selectedConversation->receiver_id,
        );

        $this->reset('body');
        $this->dispatch('scroll-bottom');

        $this->loadedMessages->push($message);
    }

    public function mount()
    {
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}

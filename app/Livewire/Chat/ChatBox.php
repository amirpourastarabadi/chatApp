<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;
    public $paginatVariable = 10;
    public $perPage = 10;

    protected $listeners = [
        'nextPage',
    ];

    public function nextPage()
    {
        $this->paginatVariable += $this->perPage;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $total = $this->selectedConversation->messages()->count();
        $this->loadedMessages = $this->selectedConversation
            ->messages()
            ->skip($total - $this->paginatVariable)
            ->take($this->paginatVariable)
            ->get();
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
        $this->dispatch('chat.chat-list', 'refresh');
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

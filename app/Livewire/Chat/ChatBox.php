<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use App\Notifications\MessageSent;
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

    public function getListeners(): array
    {
        $authUser = auth()->id();
        return [
            'nextPage',
            "echo-private:users.{$authUser},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastNotifications',
        ];
    }

    public function broadcastNotifications($event)
    {
        if ($event['type'] === MessageSent::class) {
            if ($this->selectedConversation->id == $event['conversation_id']) {
                $commingMessage = Message::findOrFail($event['message_id']);
                $this->dispatch('scroll-bottom');
                $this->loadedMessages->push($commingMessage);
                $this->dispatch('chat.chat-list', 'refresh');
            }
        }
    }

    public function nextPage()
    {
        $this->paginatVariable += $this->perPage;
        $this->loadMessages();
        $this->dispatch('kkkkkkk');
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

        $this->selectedConversation->receiver()->notify(new MessageSent($message));
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

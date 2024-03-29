<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['receiver_id', 'sender_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastUnreadMessage()
    {
        return $this->messages()->whereNull('read_at')->where('receirver_id', $this->receiver_id)->latest('id')->limit(1);
    }

    public function unReadMessages()
    {
        return $this->messages()->whereNull('read_at')->where('receirver_id', auth()->id());
    }

    public function receiver()
    {
        return $this->belongsTo(User::class)
            ->whereIn('id', [$this->sender_id, $this->receiver_id])
            ->where('id', '!=', auth()->id());
    }

    public function sender()
    {
        return $this->belongsTo(User::class)
            ->whereIn('id', [$this->sender_id, $this->receiver_id])
            ->where('id', auth()->id());
    }

    public function cereateMessage(string $body, int $sender, int $receiver): Message
    {
        return Message::create([
            'conversation_id' => $this->getkey(),
            'body' => $body,
            'sender_id' => $sender,
            'receiver_id' => $receiver,
        ]);
    }
}

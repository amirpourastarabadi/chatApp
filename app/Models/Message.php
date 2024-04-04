<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\MessageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([MessageObserver::class])]
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'receiver_id',
        'sender_id',
        'read_at',
        'receiver_deleted_at',
        'sender_deleted_at',
        'body',
    ];

    public $appends = ['sent_at'];

    public $dates = ['read_at', 'receiver_deleted_at', 'sender_deleted_at'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function sentAt(): Attribute
    {
        return Attribute::make(get: fn () => $this->created_at->format('g:i a'));
    }

    public function body(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => decrypt($value),
            set: fn (string $value) => encrypt($value)
        );
    }
}

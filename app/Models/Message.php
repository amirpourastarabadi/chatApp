<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'reciver_id',
        'sender_id',
        'read_at',
        'reciver_deleted_at',
        'sender_deleted_at',
        'body',
    ];

    public $dates = ['read_at', 'reciver_deleted_at', 'sender_deleted_at'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function isRead()
    {
        return is_null($this->read_at);
    }
}

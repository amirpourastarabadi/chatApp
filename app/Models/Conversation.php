<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['reciver_id', 'sender_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastUnreadMessage()
    {
        return $this->messages()->whereNull('read_at')->where('reciver_id', $this->reciver_id)->latest('id')->limit(1);
    }

    public function reciver()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}

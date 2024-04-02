<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id')->orWhere('receiver_id', $this->id);
    }

    public function findOrCreateConversationWith(int $userId): Conversation
    {
        $conversation = Conversation::where(fn ($q) => $q->whereSenderId($this->id)->where('receiver_id', $userId))
            ->orWhere(fn ($q) => $q->whereSenderId($userId)->where('receiver_id', $this->id))
            ->first();

        if (is_null($conversation)) {
            $conversation = $this->conversations()->create(['receiver_id' => $userId]);
        }

        return $conversation;
    }

    public function scopeExcept(Builder $builder, array $users): Builder
    {
        return $builder->whereNotIn('id', $users);
    }

    public function markMessagesAsReadeFrom(Conversation $conversation): void
    {
        $conversation->messages()->whereNull('read_at')->where('receiver_id', $this->id)->update(['read_at' => now()]);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->getKey();
    }
}

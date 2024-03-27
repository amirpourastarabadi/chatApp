<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'reciver_id' => User::factory(),
            'sender_id' => User::factory(),
            'read_at' => null,
            'body' => fake()->paragraph(random_int(1, 3)),
        ];
    }

    public function forConversation(Conversation $conversation)
    {
        return $this->state([
            'conversation_id' => $conversation->getKey(),
            'reciver_id' => $conversation->reciver_id,
            'sender_id' => $conversation->sender_id,
        ]);
    }
}

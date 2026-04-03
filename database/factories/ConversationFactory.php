<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'user_a_id' => User::factory(),
            'user_b_id' => User::factory(),
            'order_id' => null,
            'last_message_at' => $this->faker->dateTime(),
        ];
    }

    public function withUsers(User $userA, User $userB): self
    {
        return $this->state(fn (array $attributes) => [
            'user_a_id' => $userA->id,
            'user_b_id' => $userB->id,
        ]);
    }

    public function withOrder(int $orderId): self
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => $orderId,
        ]);
    }
}

<?php

declare(strict_types=1);

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

describe('Chat Conversations', function () {
    test('user can get their conversations', function () {
        Conversation::factory()->withUsers($this->user, $this->otherUser)->create();

        $response = $this->actingAs($this->user)->getJson('/chat');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(1, 'conversations');
    });

    test('user can get or create conversation with another user', function () {
        $response = $this->actingAs($this->user)
            ->getJson("/chat/{$this->otherUser->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'conversation' => ['id', 'user_a_id', 'user_b_id'],
            ]);

        $this->assertDatabaseHas('conversations', [
            'user_a_id' => min($this->user->id, $this->otherUser->id),
            'user_b_id' => max($this->user->id, $this->otherUser->id),
        ]);
    });

    test('conversation should be created only once between two users', function () {
        $this->actingAs($this->user)->getJson("/chat/{$this->otherUser->id}");
        $this->actingAs($this->user)->getJson("/chat/{$this->otherUser->id}");

        $convCount = Conversation::where('user_a_id', min($this->user->id, $this->otherUser->id))
            ->where('user_b_id', max($this->user->id, $this->otherUser->id))
            ->count();

        expect($convCount)->toBe(1);
    });

    test('user cannot create conversation with themselves', function () {
        $response = $this->actingAs($this->user)->getJson("/chat/{$this->user->id}");

        $response->assertStatus(400)
            ->assertJson(['success' => false]);
    });
});

describe('Messages', function () {
    test('user can send a message', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        $response = $this->actingAs($this->user)->postJson(
            "/chat/{$conversation->id}/messages",
            ['body' => 'Hello!']
        );

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => [
                    'body' => 'Hello!',
                    'user_id' => $this->user->id,
                ],
            ]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $this->user->id,
            'body' => 'Hello!',
        ]);
    });

    test('unauthorized user cannot send message in conversation', function () {
        $hacker = User::factory()->create();

        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        $response = $this->actingAs($hacker)->postJson(
            "/chat/{$conversation->id}/messages",
            ['body' => 'Hack attempt!']
        );

        $response->assertStatus(403)
            ->assertJson(['success' => false]);

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Hack attempt!',
        ]);
    });

    test('message body is required', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        $response = $this->actingAs($this->user)->postJson(
            "/chat/{$conversation->id}/messages",
            ['body' => '']
        );

        $response->assertStatus(422);
    });

    test('message body cannot exceed 1000 characters', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        $longMessage = str_repeat('a', 1001);

        $response = $this->actingAs($this->user)->postJson(
            "/chat/{$conversation->id}/messages",
            ['body' => $longMessage]
        );

        $response->assertStatus(422);
    });
});

describe('Message Read Status', function () {
    test('user can mark messages as read', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        Message::factory(3)
            ->inConversation($conversation)
            ->fromUser($this->otherUser)
            ->create(['is_read' => false]);

        $response = $this->actingAs($this->user)->postJson(
            "/chat/{$conversation->id}/messages/mark-as-read"
        );

        $response->assertStatus(200)
            ->assertJson(['marked_count' => 3]);

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $conversation->id,
            'is_read' => false,
        ]);
    });

    test('unauthorized user cannot mark messages as read', function () {
        $hacker = User::factory()->create();

        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        Message::factory()
            ->inConversation($conversation)
            ->fromUser($this->otherUser)
            ->create(['is_read' => false]);

        $response = $this->actingAs($hacker)->postJson(
            "/chat/{$conversation->id}/messages/mark-as-read"
        );

        $response->assertStatus(403);
    });

    test('sender messages are not marked as read', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        Message::factory()
            ->inConversation($conversation)
            ->fromUser($this->user)
            ->create(['is_read' => false]);

        $this->actingAs($this->user)->postJson(
            "/chat/{$conversation->id}/messages/mark-as-read"
        );

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $this->user->id,
            'is_read' => false,
        ]);
    });
});

describe('Pagination', function () {
    test('user can get paginated messages', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        Message::factory(25)
            ->inConversation($conversation)
            ->create();

        $response = $this->actingAs($this->user)->getJson(
            "/chat/{$conversation->id}/messages?per_page=10"
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'messages' => [
                    'data' => [
                        '*' => ['id', 'body', 'sender', 'created_at'],
                    ],
                    'meta',
                ],
            ]);

        expect($response->json('messages.data'))->toHaveCount(10);
    });

    test('default pagination is 20 per page', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        Message::factory(25)
            ->inConversation($conversation)
            ->create();

        $response = $this->actingAs($this->user)->getJson(
            "/chat/{$conversation->id}/messages"
        );

        expect($response->json('messages.data'))->toHaveCount(20);
    });
});

describe('Unread Counts', function () {
    test('user can get total unread count', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        Message::factory(3)
            ->inConversation($conversation)
            ->fromUser($this->otherUser)
            ->create(['is_read' => false]);

        $response = $this->actingAs($this->user)->getJson('/chat/unread-count');

        $response->assertStatus(200)
            ->assertJson(['unread_count' => 3]);
    });

    test('unread count reflects multiple conversations', function () {
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $conv1 = Conversation::factory()->withUsers($this->user, $user2)->create();
        $conv2 = Conversation::factory()->withUsers($this->user, $user3)->create();

        Message::factory(2)
            ->inConversation($conv1)
            ->fromUser($user2)
            ->create(['is_read' => false]);

        Message::factory(3)
            ->inConversation($conv2)
            ->fromUser($user3)
            ->create(['is_read' => false]);

        $response = $this->actingAs($this->user)->getJson('/chat/unread-count');

        $response->assertJson(['unread_count' => 5]);
    });
});

describe('Message Deletion', function () {
    test('user can delete their own message', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        $message = Message::factory()
            ->inConversation($conversation)
            ->fromUser($this->user)
            ->create();

        $response = $this->actingAs($this->user)->deleteJson(
            "/chat/{$conversation->id}/messages/{$message->id}"
        );

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('messages', ['id' => $message->id]);
    });

    test('user cannot delete other user message', function () {
        $conversation = Conversation::factory()
            ->withUsers($this->user, $this->otherUser)
            ->create();

        $message = Message::factory()
            ->inConversation($conversation)
            ->fromUser($this->otherUser)
            ->create();

        $response = $this->actingAs($this->user)->deleteJson(
            "/chat/{$conversation->id}/messages/{$message->id}"
        );

        $response->assertStatus(403);

        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    });

    test('deleting non-existent message returns 404', function () {
        $response = $this->actingAs($this->user)->deleteJson(
            "/chat/999/messages/999"
        );

        $response->assertStatus(404);
    });
});

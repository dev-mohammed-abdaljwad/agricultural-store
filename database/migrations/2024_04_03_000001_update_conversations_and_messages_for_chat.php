<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new columns to conversations table for one-on-one chat
        Schema::table('conversations', function (Blueprint $table) {
            // Add user_a_id and user_b_id columns
            if (!Schema::hasColumn('conversations', 'user_a_id')) {
                $table->foreignId('user_a_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('conversations', 'user_b_id')) {
                $table->foreignId('user_b_id')
                    ->nullable()
                    ->after('user_a_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            // Make customer_id nullable
            if (Schema::hasColumn('conversations', 'customer_id')) {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->change();
            }

            // Make order_id nullable if not already
            if (Schema::hasColumn('conversations', 'order_id')) {
                $table->foreignId('order_id')
                    ->nullable()
                    ->change();
            }

            // Add soft deletes if not exists
            if (!Schema::hasColumn('conversations', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add unique constraint for one-on-one conversations
        try {
            Schema::table('conversations', function (Blueprint $table) {
                $table->unique(['user_a_id', 'user_b_id'], 'unique_user_pair');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }

        // Update messages table
        Schema::table('messages', function (Blueprint $table) {
            // Add user_id column if not exists
            if (!Schema::hasColumn('messages', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('conversation_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            // Make sender_id nullable for backward compatibility
            if (Schema::hasColumn('messages', 'sender_id')) {
                $table->foreignId('sender_id')
                    ->nullable()
                    ->change();
            }

            // Add soft deletes if not exists
            if (!Schema::hasColumn('messages', 'deleted_at')) {
                $table->softDeletes();
            }

            // Keep sender_type if exists - don't drop it
            // (it's still used by the application)
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'user_id')) {
                try {
                    $table->dropForeignIdFor(\App\Models\User::class, 'user_id');
                } catch (\Exception $e) {
                }
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('messages', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('conversations', function (Blueprint $table) {
            if (Schema::hasColumn('conversations', 'user_a_id')) {
                try {
                    $table->dropForeignIdFor(\App\Models\User::class, 'user_a_id');
                } catch (\Exception $e) {
                }
                $table->dropColumn('user_a_id');
            }

            if (Schema::hasColumn('conversations', 'user_b_id')) {
                try {
                    $table->dropForeignIdFor(\App\Models\User::class, 'user_b_id');
                } catch (\Exception $e) {
                }
                $table->dropColumn('user_b_id');
            }

            if (Schema::hasColumn('conversations', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

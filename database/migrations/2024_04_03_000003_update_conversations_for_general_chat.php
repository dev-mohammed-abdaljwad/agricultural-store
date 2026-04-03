<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConversationsForGeneralChat extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Add user_a_id and user_b_id for one-on-one chat
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

            // Make order_id and customer_id nullable for general conversations
            if (Schema::hasColumn('conversations', 'order_id')) {
                $table->foreignId('order_id')
                    ->nullable()
                    ->change();
            }

            if (Schema::hasColumn('conversations', 'customer_id')) {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->change();
            }

            // Remove unique constraint on order_id if it exists
            try {
                $table->dropUnique(['order_id']);
            } catch (\Exception $e) {
                // Constraint might not exist
            }

            // Add index for user lookups
            $table->index(['user_a_id', 'user_b_id']);
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (Schema::hasColumn('conversations', 'user_a_id')) {
                $table->dropForeignKeyIfExists(['user_a_id']);
                $table->dropColumn('user_a_id');
            }

            if (Schema::hasColumn('conversations', 'user_b_id')) {
                $table->dropForeignKeyIfExists(['user_b_id']);
                $table->dropColumn('user_b_id');
            }
        });
    }
}

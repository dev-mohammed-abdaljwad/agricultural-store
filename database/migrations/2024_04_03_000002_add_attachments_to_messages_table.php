<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentsToMessagesTable extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Add attachment fields if they don't exist
            if (!Schema::hasColumn('messages', 'attachment_url')) {
                $table->string('attachment_url')->nullable()->after('body');
            }

            if (!Schema::hasColumn('messages', 'attachment_type')) {
                $table->enum('attachment_type', ['image', 'file'])->nullable()->after('attachment_url');
            }

            if (!Schema::hasColumn('messages', 'attachment_name')) {
                $table->string('attachment_name')->nullable()->after('attachment_type');
            }

            // Make body nullable to allow attachment-only messages
            if (Schema::hasColumn('messages', 'body')) {
                $table->text('body')->nullable()->change();
            }

            // Add is_read if not exists
            if (!Schema::hasColumn('messages', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('attachment_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'attachment_url')) {
                $table->dropColumn('attachment_url');
            }

            if (Schema::hasColumn('messages', 'attachment_type')) {
                $table->dropColumn('attachment_type');
            }

            if (Schema::hasColumn('messages', 'attachment_name')) {
                $table->dropColumn('attachment_name');
            }

            if (Schema::hasColumn('messages', 'is_read')) {
                $table->dropColumn('is_read');
            }
        });
    }
}

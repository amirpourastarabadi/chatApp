<?php

use App\Models\Conversation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Conversation::class);

            $table->unsignedBigInteger('reciver_id');
            $table->foreign('reciver_id')->references('id')->on('users');

            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('users');

            $table->timestamp('read_at')->nullable();

            $table->softDeletes('reciver_deleted_at');
            $table->softDeletes('sender_deleted_at');

            $table->text('body')->nullable()->fulltext('message_body_full_text_index');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

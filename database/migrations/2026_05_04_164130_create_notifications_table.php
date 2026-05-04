<?php

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->uuid('tenant_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->string('type');
            $table->string('channel')->default('default');

            $table->string('recipient');
            $table->string('subject')->nullable();

            $table->json('payload');

            $table->enum('status', [
                'pending',
                'processing',
                'processed',
                'failed'
            ])->default('pending');

            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('processed_at')->nullable();
            $table->text('failure_reason')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

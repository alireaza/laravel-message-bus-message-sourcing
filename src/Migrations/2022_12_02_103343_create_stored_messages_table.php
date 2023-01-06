<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        try {
            DB::beginTransaction();

            Schema::create('stored_messages', function (Blueprint $table): void {
                $table->id();
                $table->string('message_id')->unique();
                $table->string('causation_id');
                $table->string('correlation_id');
                $table->string('name');
                $table->longText('content');
                $table->unsignedBigInteger('timestamp');
                $table->timestamps();
                $table->softDeletes();

                $table->unique(['message_id', 'causation_id', 'correlation_id', 'deleted_at']);
            });

            Schema::table('stored_messages', function (Blueprint $table) {
                $table->foreign('causation_id')->references('message_id')->on('stored_messages')->cascadeOnUpdate()->restrictOnDelete();
                $table->foreign('correlation_id')->references('message_id')->on('stored_messages')->cascadeOnUpdate()->restrictOnDelete();
            });

            $connection = config('database.default');
            $driver = config('database.connections.' . $connection . '.driver');

            if ($driver === 'pgsql') {
                DB::unprepared('CREATE OR REPLACE RULE soft_delete_stored_messages
                AS ON DELETE TO stored_messages
                DO INSTEAD UPDATE stored_messages
                SET deleted_at = CURRENT_TIMESTAMP
                WHERE id = OLD.id;');
            }

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();

            throw $throwable;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        try {
            DB::beginTransaction();

            $connection = config('database.default');
            $driver = config('database.connections.' . $connection . '.driver');

            if ($driver === 'pgsql') {
                DB::unprepared('DROP RULE IF EXISTS soft_delete_stored_messages ON stored_messages;');
            }

            Schema::dropIfExists('stored_messages');

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();

            throw $throwable;
        }
    }
};

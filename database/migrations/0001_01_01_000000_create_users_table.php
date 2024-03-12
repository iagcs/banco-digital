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
        Schema::create('users', static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('email');
            $table->string('document');
            $table->string('password');
            $table->enum('type', \Arr::pluck(\App\Enums\UserType::cases(), 'value'));

            $table->timestamps(3);

            $table->unique([
                'email',
                'document'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

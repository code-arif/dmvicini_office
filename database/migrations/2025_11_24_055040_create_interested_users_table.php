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
        Schema::create('interested_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('email')->nullable(); // remove constrained()
            $table->timestamps();

            // prevent duplicate interest by the same user for the same investment
            $table->unique(['user_id', 'investment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interested_users');
    }
};

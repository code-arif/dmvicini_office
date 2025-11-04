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
        Schema::create('access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('request_token')->unique();
            $table->enum('status', ['pending', 'provisional', 'approved', 'rejected', 'limited', 'expired'])->default('pending');

            // Verification (for 506b/506c)
            $table->string('verification_type')->nullable(); // '506b', '506c'
            $table->string('verifier_document')->nullable();
            $table->string('verifier_reference')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');

            // Notes
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_requests');
    }
};

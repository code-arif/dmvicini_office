<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('slogan_line1')->nullable();
            $table->string('slogan_line2')->nullable();
            $table->string('subscribe_title')->nullable();
            $table->text('subscribe_description')->nullable();
            $table->string('copyright')->nullable();
            $table->text('disclaimer')->nullable();
            $table->json('social_links')->nullable();
            $table->json('footer_links')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footers');
    }
};

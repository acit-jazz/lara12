<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->nullable();
            $table->mediumText('summary')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('featured')->nullable()->default(false);
            $table->string('status',20)->nullable()->default('Draft');
            $table->json('images')->nullable();
            $table->json('banners')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

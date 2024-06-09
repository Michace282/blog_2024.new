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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->enum('gender', ['0', '1'])->default(1)->nullable(); // 0 = female, 1 = male
            $table->string('phone')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('profile_image')->nullable();
            $table->enum('is_active', ['0', '1'])->default(1); // 0 = inactive, 1 = active
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('password')->nullable();
            $table->foreignId('created_by')->default(1)->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
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

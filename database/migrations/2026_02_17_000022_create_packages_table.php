<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name'); // "10 Class Pack", "5 Private Sessions"
            $table->string('type'); // class, private, product
            $table->integer('quantity'); // 10 classes, 5 privates, etc
            $table->decimal('price', 10, 2);
            $table->integer('validity_days')->nullable(); // expiration days
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('package_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->integer('remaining_uses');
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_purchases');
        Schema::dropIfExists('packages');
    }
};

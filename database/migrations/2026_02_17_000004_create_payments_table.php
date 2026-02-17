<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('payment_type'); // membership, private_class, drop_in, expense
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->dateTime('payment_date');
            $table->string('payment_method')->nullable(); // cash, card, stripe, etc.
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }
};

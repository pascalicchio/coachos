<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('max_locations')->default(1);
            $table->integer('max_coaches')->default(5);
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Seed default plans
        DB::table('plans')->insert([
            ['name' => 'Starter', 'price' => 29.00, 'max_locations' => 1, 'max_coaches' => 5, 'features' => json_encode(['Basic Scheduling', '5 Coaches', '1 Location'])],
            ['name' => 'Pro', 'price' => 59.00, 'max_locations' => 3, 'max_coaches' => 15, 'features' => json_encode(['Basic Scheduling', 'Payments', 'Private Classes', '15 Coaches', '3 Locations'])],
            ['name' => 'Business', 'price' => 99.00, 'max_locations' => 999, 'max_coaches' => 999, 'features' => json_encode(['Everything in Pro', 'Lead Management', 'Unlimited'])],
        ]);
    }
};

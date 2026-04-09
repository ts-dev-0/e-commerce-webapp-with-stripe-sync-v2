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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('order_number');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('total_amount');
            $table->timestamp('ordered_at')->nullable();

            $table->string('full_name');
            $table->string('postal_code', 7);
            $table->string('prefecture');
            $table->string('city');
            $table->string('address_line');
            $table->string('phone_number');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

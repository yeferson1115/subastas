<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auction_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_product_id')->constrained('auction_products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['auction_product_id', 'amount']);
            $table->index(['auction_product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_bids');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auction_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auctioneer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->date('auction_start_date');
            $table->date('auction_end_date');
            $table->time('auction_start_time');
            $table->time('auction_end_time');
            $table->decimal('base_price', 15, 2);
            $table->string('technical_sheet_path')->nullable();
            $table->string('terms_path')->nullable();
            $table->string('product_type');
            $table->json('product_details')->nullable();
            $table->string('location');
            $table->string('contact_phone', 50);
            $table->boolean('mandatory_visit')->default(false);
            $table->unsignedInteger('quantity')->default(1);
            $table->longText('detail');
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_products');
    }
};

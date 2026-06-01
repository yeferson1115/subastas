<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('user_type', 30)->index();
            $table->unsignedSmallInteger('duration_months');
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['user_type', 'duration_months']);
        });

        $now = now();
        foreach ([User::TYPE_AUCTIONEER, User::TYPE_BIDDER] as $userType) {
            foreach ([1, 6, 12] as $duration) {
                DB::table('plans')->insert([
                    'user_type' => $userType,
                    'duration_months' => $duration,
                    'price' => 0,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

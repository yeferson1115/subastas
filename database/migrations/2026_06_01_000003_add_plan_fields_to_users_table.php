<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'plan_id')) {
                $table->foreignId('plan_id')->nullable()->after('user_type')->constrained('plans')->nullOnDelete();
            }

            if (! Schema::hasColumn('users', 'plan_started_at')) {
                $table->date('plan_started_at')->nullable()->after('plan_id');
            }

            if (! Schema::hasColumn('users', 'plan_expires_at')) {
                $table->date('plan_expires_at')->nullable()->after('plan_started_at')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'plan_id')) {
                $table->dropConstrainedForeignId('plan_id');
            }

            foreach (['plan_started_at', 'plan_expires_at'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

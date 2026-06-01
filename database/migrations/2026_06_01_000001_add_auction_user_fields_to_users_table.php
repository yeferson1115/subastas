<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type', 30)->default('ofertante')->index();
            }

            if (! Schema::hasColumn('users', 'auctioneer_client_type')) {
                $table->string('auctioneer_client_type', 30)->nullable()->index();
            }

            if (! Schema::hasColumn('users', 'document_type')) {
                $table->string('document_type', 50)->nullable();
            }

            if (! Schema::hasColumn('users', 'document_number')) {
                $table->string('document_number', 80)->nullable()->index();
            }

            if (! Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable();
            }

            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city', 120)->nullable();
            }

            if (! Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable()->index();
            }

            if (! Schema::hasColumn('users', 'company_document_number')) {
                $table->string('company_document_number', 80)->nullable()->index();
            }

            if (! Schema::hasColumn('users', 'company_legal_representative')) {
                $table->string('company_legal_representative')->nullable();
            }

            if (! Schema::hasColumn('users', 'company_phone')) {
                $table->string('company_phone', 50)->nullable();
            }

            if (! Schema::hasColumn('users', 'company_address')) {
                $table->string('company_address')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'user_type',
                'auctioneer_client_type',
                'document_type',
                'document_number',
                'address',
                'city',
                'company_name',
                'company_document_number',
                'company_legal_representative',
                'company_phone',
                'company_address',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

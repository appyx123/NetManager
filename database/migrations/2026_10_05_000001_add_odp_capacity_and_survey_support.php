<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah odp_available_ports pada network_assets
        if (Schema::hasTable('network_assets')) {
            Schema::table('network_assets', function (Blueprint $table) {
                if (!Schema::hasColumn('network_assets', 'odp_available_ports')) {
                    $table->integer('odp_available_ports')->nullable()->default(0)->after('port_capacity');
                }
            });

            // Inisialisasi kapasitas port ODP yang sudah ada
            DB::table('network_assets')
                ->where('type', 'ODP')
                ->where(function ($q) {
                    $q->whereNull('odp_available_ports')->orWhere('odp_available_ports', 0);
                })
                ->update([
                    'odp_available_ports' => DB::raw('COALESCE(port_capacity, 8)'),
                    'port_capacity' => DB::raw('COALESCE(port_capacity, 8)'),
                ]);
        }

        // 2. Tambah lead_id, odp_id, odp_released pada tickets
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (!Schema::hasColumn('tickets', 'lead_id')) {
                    $table->foreignId('lead_id')
                        ->nullable()
                        ->after('customer_id')
                        ->constrained('leads')
                        ->nullOnDelete();
                }
                if (!Schema::hasColumn('tickets', 'odp_id')) {
                    $table->foreignId('odp_id')
                        ->nullable()
                        ->after('router_id')
                        ->constrained('network_assets')
                        ->nullOnDelete();
                }
                if (!Schema::hasColumn('tickets', 'odp_released')) {
                    $table->boolean('odp_released')->default(false)->after('odp_id');
                }
            });
        }

        // 3. Tambah odp_id pada leads (opsional rekomendasi dari survey)
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn('leads', 'odp_id')) {
                    $table->foreignId('odp_id')
                        ->nullable()
                        ->after('package_id')
                        ->constrained('network_assets')
                        ->nullOnDelete();
                }
                if (!Schema::hasColumn('leads', 'odp_port')) {
                    $table->string('odp_port')->nullable()->after('odp_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'odp_id')) {
                    $table->dropConstrainedForeignId('odp_id');
                }
                if (Schema::hasColumn('leads', 'odp_port')) {
                    $table->dropColumn('odp_port');
                }
            });
        }

        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (Schema::hasColumn('tickets', 'lead_id')) {
                    $table->dropConstrainedForeignId('lead_id');
                }
                if (Schema::hasColumn('tickets', 'odp_id')) {
                    $table->dropConstrainedForeignId('odp_id');
                }
                if (Schema::hasColumn('tickets', 'odp_released')) {
                    $table->dropColumn('odp_released');
                }
            });
        }

        if (Schema::hasTable('network_assets') && Schema::hasColumn('network_assets', 'odp_available_ports')) {
            Schema::table('network_assets', function (Blueprint $table) {
                $table->dropColumn('odp_available_ports');
            });
        }
    }
};

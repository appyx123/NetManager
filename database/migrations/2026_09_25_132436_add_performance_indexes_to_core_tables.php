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
        $indexes = [
            'leads'         => ['status', 'marketing_id'],
            'tickets'       => ['status', 'customer_id'],
            'invoices'      => ['status', 'subscription_id'],
            'subscriptions' => ['customer_id', 'status'],
            'customers'     => ['user_id'],
            'sessions'      => ['last_activity'],
            'audit_logs'    => ['user_id'],
        ];

        foreach ($indexes as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column)) {
                    continue;
                }

                $indexName = "{$table}_{$column}_index";

                try {
                    Schema::table($table, function (Blueprint $tableBlueprint) use ($column, $indexName) {
                        $tableBlueprint->index($column, $indexName);
                    });
                } catch (\Throwable $e) {
                    // Indeks dilewati jika sudah pernah dibuat oleh script ad-hoc
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $indexes = [
            'leads'         => ['status', 'marketing_id'],
            'tickets'       => ['status', 'customer_id'],
            'invoices'      => ['status', 'subscription_id'],
            'subscriptions' => ['customer_id', 'status'],
            'customers'     => ['user_id'],
            'sessions'      => ['last_activity'],
            'audit_logs'    => ['user_id'],
        ];

        foreach ($indexes as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                $indexName = "{$table}_{$column}_index";
                try {
                    Schema::table($table, function (Blueprint $tableBlueprint) use ($indexName) {
                        $tableBlueprint->dropIndex($indexName);
                    });
                } catch (\Throwable $e) {
                    // Ignored jika index tidak ada
                }
            }
        }
    }
};

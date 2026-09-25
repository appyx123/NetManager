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
        Schema::dropIfExists('device_configs');
        Schema::dropIfExists('network_configs');
        Schema::dropIfExists('installation_forms');
        Schema::dropIfExists('survey_forms');
        Schema::dropIfExists('repair_forms');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('survey_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->date('survey_date')->nullable();
            $table->enum('survey_status', ['layak', 'tidak_layak'])->nullable();
            $table->text('survey_notes')->nullable();
            $table->string('location_photo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('installation_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->date('installation_date')->nullable();
            $table->enum('connection_type', ['fiber', 'wireless'])->nullable();
            $table->integer('cable_length')->nullable();
            $table->enum('status', ['berhasil', 'gagal'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('device_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->unique()->constrained('installation_forms')->cascadeOnDelete();
            $table->string('device_type')->nullable();
            $table->string('device_brand')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('device_condition')->nullable();
            $table->timestamps();
        });

        Schema::create('network_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->unique()->constrained('installation_forms')->cascadeOnDelete();
            $table->foreignId('router_id')->nullable()->constrained('network_assets')->nullOnDelete();
            $table->string('vlan_id')->nullable();
            $table->string('odp_port')->nullable();
            $table->string('port_interface')->nullable();
            $table->string('connection_mode')->nullable();
            $table->timestamps();
        });

        Schema::create('repair_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->date('repair_date')->nullable();
            $table->text('issue_description')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });
    }
};

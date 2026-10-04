<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $technician_id
 * @property string $status
 * @property string|null $type
 * @property string|null $subject
 * @property string|null $description
 * @property string|null $technical_notes
 * @property string|null $location_photo_path
 * @property string|null $speedtest_photo_path
 * @property string|null $evidence_photo_path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Ticket extends Model
{
    protected $fillable = [
        'customer_id',
        'technician_id',
        'type',
        'subject',
        'status',
        'description',
        'technical_notes',
        'notes',
        'completion_date',

        // --- 1. SURVEY ---
        'survey_status',
        'survey_date',
        'survey_notes',
        'location_obstacle',
        'location_photo_path',

        // --- 2. INSTALASI ---
        'installation_date',
        'connection_type',
        'cable_length',
        'mounting_position',
        'installation_status',
        'installation_notes',

        // --- 3. PERANGKAT ---
        'device_type',
        'device_brand',
        'device_sn',
        'device_mac',
        'device_condition',

        // --- 4. JARINGAN ---
        'lead_id',
        'router_id',
        'odp_id',
        'odp_released',
        'port_interface',
        'vlan_id',
        'odp_port',
        'olt_source',
        'connection_mode',
        'dbm_signal',

        // --- 5. AKUN ---
        'pppoe_username',
        'pppoe_password',
        'service_status',

        // --- 6. UJI KONEKSI ---
        'connectivity_status',
        'speed_test_result',
        'latency',
        'speedtest_photo_path',

        // --- 7. SERAH TERIMA ---
        'internet_active_confirmation',
        'handover_date',
        'final_technician_notes',
        'completed_at',
        'evidence_photo_path', // Foto Modem
    ];

    protected $casts = [
        'survey_date' => 'date',
        'installation_date' => 'date',
        'handover_date' => 'date',
        'completed_at' => 'datetime',
        'internet_active_confirmation' => 'boolean',
        'odp_released' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    public function router()
    {
        return $this->belongsTo(NetworkAsset::class, 'router_id');
    }
    public function odp()
    {
        return $this->belongsTo(NetworkAsset::class, 'odp_id');
    }

    /**
     * Mengembalikan kuota port ODP yang sebelumnya dipesan jika instalasi batal / gagal.
     */
    public function releaseOdpPort(): bool
    {
        if ($this->odp_id && !$this->odp_released) {
            $odp = NetworkAsset::find($this->odp_id);
            if ($odp) {
                $odp->increment('odp_available_ports');
            }
            $this->update(['odp_released' => true]);
            return true;
        }
        return false;
    }
}

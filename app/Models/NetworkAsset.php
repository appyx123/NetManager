<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property string|null $ip_address
 * @property string|null $brand
 * @property string $type
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class NetworkAsset extends Model
{
    protected $fillable = [
        'name', 
        'location', 
        'ip_address', 
        'brand', 
        'type', // OLT, Router, AP, ODP
        'is_active',
        'api_username',
        'api_password',
        'api_port',
        'port_capacity',
        'odp_available_ports',
        'coordinates',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'api_password'        => 'encrypted',
        'api_port'            => 'integer',
        'port_capacity'       => 'integer',
        'odp_available_ports' => 'integer',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'router_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'router_id');
    }
}
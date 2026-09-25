<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $marketing_id
 * @property int|null $package_id
 * @property string $name
 * @property string|null $mother_name
 * @property string|null $phone
 * @property string|null $phone_backup
 * @property string|null $email
 * @property string|null $customer_type
 * @property string|null $business_name
 * @property string|null $emergency_name
 * @property string|null $emergency_phone
 * @property string|null $emergency_relation
 * @property string|null $address_ktp
 * @property string|null $address_installation
 * @property string|null $address
 * @property string|null $rt_rw
 * @property string|null $village
 * @property string|null $district
 * @property string|null $city
 * @property string|null $province
 * @property string|null $postal_code
 * @property string|null $landmark
 * @property string|null $coordinates
 * @property string|null $promo_code
 * @property float|null $installation_fee
 * @property string|null $status
 * @property string|null $source
 * @property \Carbon\Carbon|null $survey_date
 * @property \Carbon\Carbon|null $installation_date
 * @property \Carbon\Carbon|null $registered_date
 * @property string|null $preferred_time
 * @property string|null $notes_summary
 * @property string|null $notes_obstacle
 * @property string|null $notes_special
 * @property string|null $ktp_image_path
 * @property string|null $house_image_path
 * @property string|null $customer_image_path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketing_id',
        'name',
        'mother_name',
        'phone',
        'phone_backup',
        'email',
        'customer_type',
        'business_name',
        'emergency_name',
        'emergency_phone',
        'emergency_relation',
        'address_ktp',
        'address_installation',
        'address',
        'rt_rw',
        'village',
        'district',
        'city',
        'province',
        'postal_code',
        'landmark',
        'coordinates',
        'package_id',
        'promo_code',
        'installation_fee',
        'status',
        'source',
        'registered_date',
        'survey_date',
        'installation_date',
        'preferred_time',
        'notes_summary',
        'notes_obstacle',
        'notes_special',
        'ktp_image_path',
        'house_image_path',
        'customer_image_path',
    ];

    protected $casts = [
        'survey_date' => 'date',
        'installation_date' => 'date',
        'registered_date' => 'date',
    ];

    // Relasi ke Paket Internet
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Relasi ke User Marketing
    public function marketing()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }

    // Relasi: Prospek (Lead) yang sudah diconvert memiliki satu data Customer
    public function customerProfile()
    {
        return $this->hasOne(Customer::class, 'lead_id');
    }
}

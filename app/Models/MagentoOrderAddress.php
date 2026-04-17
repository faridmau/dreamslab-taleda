<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoOrderAddress extends Model
{
    protected $connection = 'magento';

    protected $table = 'sales_order_address';

    protected $primaryKey = 'entity_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    protected $casts = [
        'customer_id' => 'int',
        'order_id' => 'int',
    ];

    /**
     * Get the order this address belongs to
     */
    public function order()
    {
        return $this->belongsTo(MagentoOrder::class, 'parent_id', 'entity_id');
    }

    /**
     * Get the customer this address belongs to
     */
    public function customer()
    {
        return $this->belongsTo(MagentoCustomer::class, 'customer_id', 'entity_id');
    }

    /**
     * Get formatted full address
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->street,
            $this->city,
            $this->region,
            $this->postcode,
            $this->country_id,
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Get address type label
     */
    public function getAddressTypeLabelAttribute(): string
    {
        return match ($this->address_type) {
            'billing' => 'Billing Address',
            'shipping' => 'Shipping Address',
            default => ucfirst($this->address_type ?? 'Unknown'),
        };
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoOrderAddress is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoOrderAddress is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoOrderAddress is read-only and cannot be deleted.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoOrder extends Model
{
    protected $connection = 'magento';

    protected $table = 'sales_order';

    protected $primaryKey = 'entity_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    protected $casts = [
        'customer_id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'grand_total' => 'float',
        'subtotal' => 'float',
        'tax_amount' => 'float',
        'shipping_amount' => 'float',
        'discount_amount' => 'float',
    ];

    protected $appends = ['status_label'];

    /**
     * Get the customer that owns this order
     */
    public function customer()
    {
        return $this->belongsTo(MagentoCustomer::class, 'customer_id', 'entity_id');
    }

    /**
     * Get order items
     */
    public function items()
    {
        return $this->hasMany(MagentoOrderItem::class, 'order_id', 'entity_id');
    }

    /**
     * Get order addresses (billing and shipping)
     */
    public function addresses()
    {
        return $this->hasMany(MagentoOrderAddress::class, 'parent_id', 'entity_id');
    }

    /**
     * Get billing address
     */
    public function billingAddress()
    {
        return $this->hasOne(MagentoOrderAddress::class, 'parent_id', 'entity_id')
            ->where('address_type', 'billing');
    }

    /**
     * Get shipping address
     */
    public function shippingAddress()
    {
        return $this->hasOne(MagentoOrderAddress::class, 'parent_id', 'entity_id')
            ->where('address_type', 'shipping');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'complete' => 'Complete',
            'closed' => 'Closed',
            'canceled' => 'Canceled',
            'holded' => 'On Hold',
            'payment_review' => 'Payment Review',
            'fraud' => 'Fraud',
            default => ucfirst($this->status ?? 'Unknown'),
        };
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoOrder is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoOrder is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoOrder is read-only and cannot be deleted.');
    }
}

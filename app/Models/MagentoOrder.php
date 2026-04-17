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

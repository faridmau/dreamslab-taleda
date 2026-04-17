<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoOrderItem extends Model
{
    protected $connection = 'magento';

    protected $table = 'sales_order_item';

    protected $primaryKey = 'item_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    /**
     * Get the order that contains this item
     */
    public function order()
    {
        return $this->belongsTo(MagentoOrder::class, 'order_id', 'entity_id');
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoOrderItem is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoOrderItem is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoOrderItem is read-only and cannot be deleted.');
    }
}

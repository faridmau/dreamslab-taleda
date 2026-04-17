<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoStockItem extends Model
{
    protected $connection = 'magento';

    protected $table = 'cataloginventory_stock_item';

    protected $primaryKey = 'item_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    public function product()
    {
        return $this->belongsTo(MagentoProduct::class, 'product_id', 'entity_id');
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoStockItem is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoStockItem is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoStockItem is read-only and cannot be deleted.');
    }
}

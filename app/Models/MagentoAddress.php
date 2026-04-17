<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoAddress extends Model
{
    protected $connection = 'magento';

    protected $table = 'customer_address_entity';

    protected $primaryKey = 'entity_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    /**
     * Get the customer that owns this address
     */
    public function customer()
    {
        return $this->belongsTo(MagentoCustomer::class, 'parent_id', 'entity_id');
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoAddress is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoAddress is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoAddress is read-only and cannot be deleted.');
    }
}

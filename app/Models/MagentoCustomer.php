<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoCustomer extends Model
{
    protected $connection = 'magento';

    protected $table = 'customer_entity';

    protected $primaryKey = 'entity_id';

    public $timestamps = true;

    protected $guarded = ['*'];

    protected $fillable = [];

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoCustomer is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoCustomer is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoCustomer is read-only and cannot be deleted.');
    }

    /**
     * Get the customer's orders
     */
    public function orders()
    {
        return $this->hasMany(MagentoOrder::class, 'customer_id', 'entity_id');
    }

    /**
     * Get the customer's addresses
     */
    public function addresses()
    {
        return $this->hasMany(MagentoAddress::class, 'parent_id', 'entity_id');
    }
}

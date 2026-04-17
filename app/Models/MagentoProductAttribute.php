<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoProductAttribute extends Model
{
    protected $connection = 'magento';

    protected $table = 'catalog_product_entity_varchar';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoProductAttribute is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoProductAttribute is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoProductAttribute is read-only and cannot be deleted.');
    }
}

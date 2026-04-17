<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoCategory extends Model
{
    protected $connection = 'magento';

    protected $table = 'catalog_category_entity';

    protected $primaryKey = 'entity_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    public function products()
    {
        return $this->belongsToMany(
            MagentoProduct::class,
            'catalog_category_product',
            'category_id',
            'product_id',
            'entity_id',
            'entity_id'
        );
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoCategory is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoCategory is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoCategory is read-only and cannot be deleted.');
    }
}

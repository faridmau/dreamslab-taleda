<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoProductMedia extends Model
{
    protected $connection = 'magento';

    protected $table = 'catalog_product_entity_media_gallery';

    protected $primaryKey = 'value_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    /**
     * Note: catalog_product_entity_media_gallery does NOT have entity_id.
     * To get media for a product, query through:
     * catalog_product_entity_media_gallery_value (has entity_id, value_id)
     * or
     * catalog_product_entity_media_gallery_value_to_entity (join table with entity_id, value_id)
     */
    public function product()
    {
        return $this->belongsTo(MagentoProduct::class, 'value_id', 'entity_id');
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoProductMedia is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoProductMedia is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoProductMedia is read-only and cannot be deleted.');
    }
}

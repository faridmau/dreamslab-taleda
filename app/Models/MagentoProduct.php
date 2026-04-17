<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MagentoProduct extends Model
{
    protected $connection = 'magento';

    protected $table = 'catalog_product_entity';

    protected $primaryKey = 'entity_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    // Magento entity_type_id for catalog_product = 4
    private const ENTITY_TYPE_ID = 4;

    // EAV type tables
    private const EAV_TYPES = [
        'varchar' => 'catalog_product_entity_varchar',
        'int' => 'catalog_product_entity_int',
        'decimal' => 'catalog_product_entity_decimal',
        'text' => 'catalog_product_entity_text',
        'datetime' => 'catalog_product_entity_datetime',
    ];

    protected $appends = [
        'name', 'price', 'special_price', 'status',
        'visibility', 'description', 'short_description',
        'weight', 'url_key', 'meta_title', 'meta_description',
        'tax_class_id', 'cost', 'status_label', 'visibility_label',
    ];

    protected ?array $eavCache = null;

    /**
     * Load ALL EAV attributes for this product in 5 queries (one per type table),
     * keyed by attribute code. Results are cached per model instance.
     */
    public function loadEavAttributes(): array
    {
        if ($this->eavCache !== null) {
            return $this->eavCache;
        }

        $db = DB::connection($this->connection);

        // Get all attribute_id → code mappings for products
        $attributes = $db->table('eav_attribute')
            ->where('entity_type_id', self::ENTITY_TYPE_ID)
            ->pluck('attribute_code', 'attribute_id');

        $entityId = $this->entity_id;
        $storeId = 0; // 0 = global/default scope

        $result = [];

        foreach (self::EAV_TYPES as $type => $typeTable) {
            $rows = $db->table($typeTable)
                ->where('entity_id', $entityId)
                ->whereIn('store_id', [0, $storeId])
                ->orderBy('store_id', 'desc') // store-level overrides global
                ->get(['attribute_id', 'value', 'store_id']);

            foreach ($rows as $row) {
                $code = $attributes[$row->attribute_id] ?? null;
                if ($code && ! isset($result[$code])) {
                    // first hit wins because we sorted desc (store > global)
                    $result[$code] = $row->value;
                }
            }
        }

        $this->eavCache = $result;

        return $result;
    }

    public function getEavAttribute(string $code, mixed $default = null): mixed
    {
        return $this->loadEavAttributes()[$code] ?? $default;
    }

    // ─── Attribute Accessors ─────────────────────────────────

    public function getNameAttribute(): ?string
    {
        return $this->getEavAttribute('name');
    }

    public function getPriceAttribute(): ?float
    {
        $v = $this->getEavAttribute('price');

        return $v !== null ? (float) $v : null;
    }

    public function getSpecialPriceAttribute(): ?float
    {
        $v = $this->getEavAttribute('special_price');

        return $v !== null ? (float) $v : null;
    }

    public function getStatusAttribute(): ?int
    {
        $v = $this->getEavAttribute('status');

        return $v !== null ? (int) $v : null;
    }

    public function getVisibilityAttribute(): ?int
    {
        $v = $this->getEavAttribute('visibility');

        return $v !== null ? (int) $v : null;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->getEavAttribute('description');
    }

    public function getShortDescriptionAttribute(): ?string
    {
        return $this->getEavAttribute('short_description');
    }

    public function getWeightAttribute(): ?float
    {
        $v = $this->getEavAttribute('weight');

        return $v !== null ? (float) $v : null;
    }

    public function getUrlKeyAttribute(): ?string
    {
        return $this->getEavAttribute('url_key');
    }

    public function getMetaTitleAttribute(): ?string
    {
        return $this->getEavAttribute('meta_title');
    }

    public function getMetaDescriptionAttribute(): ?string
    {
        return $this->getEavAttribute('meta_description');
    }

    public function getTaxClassIdAttribute(): ?int
    {
        $v = $this->getEavAttribute('tax_class_id');

        return $v !== null ? (int) $v : null;
    }

    public function getCostAttribute(): ?float
    {
        $v = $this->getEavAttribute('cost');

        return $v !== null ? (float) $v : null;
    }

    // ─── Label Helpers ───────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ((int) ($this->status ?? 0)) {
            1 => 'Enabled',
            2 => 'Disabled',
            default => 'Unknown',
        };
    }

    public function getVisibilityLabelAttribute(): string
    {
        return match ((int) ($this->visibility ?? 0)) {
            1 => 'Not Visible Individually',
            2 => 'Catalog',
            3 => 'Search',
            4 => 'Catalog, Search',
            default => 'Unknown',
        };
    }

    // ─── Relations ───────────────────────────────────────────

    public function stockItem()
    {
        return $this->hasOne(MagentoStockItem::class, 'product_id', 'entity_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            MagentoCategory::class,
            'catalog_category_product',
            'product_id',
            'category_id',
            'entity_id',
            'entity_id'
        )->withPivot('position');
    }

    // ─── Scopes ──────────────────────────────────────────────

    public function scopeEnabled($query)
    {
        // Join status EAV to filter — used in list queries
        return $query->whereIn('entity_id', function ($sub) {
            $sub->select('entity_id')
                ->from('catalog_product_entity_int')
                ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'catalog_product_entity_int.attribute_id')
                ->where('eav_attribute.attribute_code', 'status')
                ->where('catalog_product_entity_int.value', 1)
                ->where('catalog_product_entity_int.store_id', 0);
        });
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoProduct is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoProduct is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoProduct is read-only and cannot be deleted.');
    }
}

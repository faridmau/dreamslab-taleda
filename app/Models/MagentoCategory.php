<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MagentoCategory extends Model
{
    protected $connection = 'magento';

    protected $table = 'catalog_category_entity';

    protected $primaryKey = 'entity_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    // Magento entity_type_id for catalog_category = 3
    private const ENTITY_TYPE_ID = 3;

    // EAV type tables
    private const EAV_TYPES = [
        'varchar' => 'catalog_category_entity_varchar',
        'int' => 'catalog_category_entity_int',
        'decimal' => 'catalog_category_entity_decimal',
        'text' => 'catalog_category_entity_text',
        'datetime' => 'catalog_category_entity_datetime',
    ];

    protected $appends = [
        'name', 'description', 'meta_title', 'meta_keywords', 'meta_description',
        'is_active', 'image', 'display_mode', 'landing_page', 'is_anchor',
    ];

    protected ?array $eavCache = null;

    /**
     * Load ALL EAV attributes for this category in 5 queries (one per type table),
     * keyed by attribute code. Results are cached per model instance.
     */
    public function loadEavAttributes(): array
    {
        if ($this->eavCache !== null) {
            return $this->eavCache;
        }

        $db = DB::connection($this->connection);

        // Get all attribute_id → code mappings for categories
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

    public function getDescriptionAttribute(): ?string
    {
        return $this->getEavAttribute('description');
    }

    public function getMetaTitleAttribute(): ?string
    {
        return $this->getEavAttribute('meta_title');
    }

    public function getMetaKeywordsAttribute(): ?string
    {
        return $this->getEavAttribute('meta_keywords');
    }

    public function getMetaDescriptionAttribute(): ?string
    {
        return $this->getEavAttribute('meta_description');
    }

    public function getIsActiveAttribute(): ?int
    {
        $v = $this->getEavAttribute('is_active');

        return $v !== null ? (int) $v : null;
    }

    public function getImageAttribute(): ?string
    {
        return $this->getEavAttribute('image');
    }

    public function getDisplayModeAttribute(): ?string
    {
        return $this->getEavAttribute('display_mode');
    }

    public function getLandingPageAttribute(): ?int
    {
        $v = $this->getEavAttribute('landing_page');

        return $v !== null ? (int) $v : null;
    }

    public function getIsAnchorAttribute(): ?int
    {
        $v = $this->getEavAttribute('is_anchor');

        return $v !== null ? (int) $v : null;
    }

    // ─── Status Label Helper ─────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return (int) ($this->is_active ?? 0) === 1 ? 'Active' : 'Inactive';
    }

    // ─── Relations ───────────────────────────────────────────

    /**
     * Get products assigned to this category.
     * Links through catalog_category_product table with position ordering.
     */
    public function products()
    {
        return $this->belongsToMany(
            MagentoProduct::class,
            'catalog_category_product',
            'category_id',
            'product_id',
            'entity_id',
            'entity_id'
        )->withPivot('position')
            ->orderBy('position');
    }

    /**
     * Get parent category (if not root).
     */
    public function parent()
    {
        return $this->belongsTo(MagentoCategory::class, 'parent_id', 'entity_id');
    }

    /**
     * Get child categories.
     */
    public function children()
    {
        return $this->hasMany(MagentoCategory::class, 'parent_id', 'entity_id');
    }

    // ─── Scopes ──────────────────────────────────────────────

    /**
     * Filter for active categories only.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('entity_id', function ($sub) {
            $sub->select('entity_id')
                ->from('catalog_category_entity_int')
                ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'catalog_category_entity_int.attribute_id')
                ->where('eav_attribute.attribute_code', 'is_active')
                ->where('catalog_category_entity_int.value', 1)
                ->where('catalog_category_entity_int.store_id', 0);
        });
    }

    /**
     * Filter for root categories.
     */
    public function scopeRoot($query)
    {
        return $query->where('parent_id', 1);
    }

    /**
     * Filter for non-root categories.
     */
    public function scopeNonRoot($query)
    {
        return $query->where('parent_id', '!=', 1);
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

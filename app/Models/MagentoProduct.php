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
        'brand', 'reference', 'model', 'year', 'condition',
        'box', 'warranty', 'gender', 'material_case', 'material_bracelet',
        'case_diameter', 'waterproof', 'dial_color', 'crystal_type',
        'movement', 'functions', 'bezel_material', 'dial_numbers',
        'bracelet_color', 'closure_type', 'closure_material',
        'case_material_label', 'bracelet_color_label', 'buckle_material_label',
        'clasp_label', 'dial_label', 'dial_numerals_label', 'glass_label',
        'material_bezel_label', 'waterproof_label', 'is_new_label',
        'movement_type_label', 'strap_label', 'gender_label', 'diameter_case_label',
        'jew_weight', 'ringsize', 'jew_stones', 'peso_diamante', 'colore_stone', 'gold_label',
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

    /**
     * Resolve option ID to human-readable label
     * (Magento select/multiselect attributes store option IDs, not values)
     */
    public function getOptionLabel(mixed $optionId): ?string
    {
        if (! $optionId) {
            return null;
        }

        $db = DB::connection($this->connection);

        return $db->table('eav_attribute_option_value')
            ->where('option_id', $optionId)
            ->where('store_id', 0)
            ->value('value');
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

    // ─── Watch-Specific Attributes ──────────────────────────

    public function getBrandAttribute(): ?string
    {
        return $this->getEavAttribute('brand');
    }

    public function getReferenceAttribute(): ?string
    {
        return $this->getEavAttribute('reference');
    }

    public function getModelAttribute(): ?string
    {
        return $this->getEavAttribute('model');
    }

    public function getYearAttribute(): ?string
    {
        return $this->getEavAttribute('year');
    }

    public function getConditionAttribute(): ?string
    {
        return $this->getEavAttribute('condition');
    }

    public function getBoxAttribute(): ?string
    {
        return $this->getEavAttribute('box');
    }

    public function getWarrantyAttribute(): ?string
    {
        return $this->getEavAttribute('warranty');
    }

    public function getGenderAttribute(): ?string
    {
        return $this->getEavAttribute('gender');
    }

    public function getMaterialCaseAttribute(): ?string
    {
        return $this->getEavAttribute('material_case');
    }

    public function getMaterialBraceletAttribute(): ?string
    {
        return $this->getEavAttribute('material_bracelet');
    }

    public function getCaseDiameterAttribute(): ?string
    {
        return $this->getEavAttribute('case_diameter');
    }

    public function getWaterproofAttribute(): ?string
    {
        return $this->getEavAttribute('waterproof');
    }

    public function getDialColorAttribute(): ?string
    {
        return $this->getEavAttribute('dial_color');
    }

    public function getCrystalTypeAttribute(): ?string
    {
        return $this->getEavAttribute('crystal_type');
    }

    public function getMovementAttribute(): ?string
    {
        return $this->getEavAttribute('movement');
    }

    public function getFunctionsAttribute(): ?string
    {
        return $this->getEavAttribute('functions');
    }

    public function getBezelMaterialAttribute(): ?string
    {
        return $this->getEavAttribute('bezel_material');
    }

    public function getDialNumbersAttribute(): ?string
    {
        return $this->getEavAttribute('dial_numbers');
    }

    public function getBraceletColorAttribute(): ?string
    {
        return $this->getEavAttribute('bracelet_color');
    }

    public function getClosureTypeAttribute(): ?string
    {
        return $this->getEavAttribute('closure_type');
    }

    public function getClosureMaterialAttribute(): ?string
    {
        return $this->getEavAttribute('closure_material');
    }

    // ─── Watch-Specific Attribute Labels (Option ID → Label) ─

    public function getCaseMaterialLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('case_material');

        return $this->getOptionLabel($optionId);
    }

    public function getBraceletColorLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('field_bracelet_color');

        return $this->getOptionLabel($optionId);
    }

    public function getBuckleMaterialLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('field_buckle_material');

        return $this->getOptionLabel($optionId);
    }

    public function getClaspLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('field_clasp');

        return $this->getOptionLabel($optionId);
    }

    public function getDialLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('field_dial');

        return $this->getOptionLabel($optionId);
    }

    public function getDialNumeralsLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('field_dial_numerals');

        return $this->getOptionLabel($optionId);
    }

    public function getGlassLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('field_glass');

        return $this->getOptionLabel($optionId);
    }

    public function getMaterialBezelLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('field_material_bezel');

        return $this->getOptionLabel($optionId);
    }

    public function getWaterproofLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('field_waterproof');

        return $this->getOptionLabel($optionId);
    }

    public function getIsNewLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('is_new');

        return $this->getOptionLabel($optionId);
    }

    public function getMovementTypeLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('movement_type');

        return $this->getOptionLabel($optionId);
    }

    public function getStrapLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('strap');

        return $this->getOptionLabel($optionId);
    }

    public function getGenderLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('gender');

        return $this->getOptionLabel($optionId);
    }

    public function getDiameterCaseLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('diameter_case');

        return $this->getOptionLabel($optionId);
    }

    // ─── Jewelry-Specific Attributes ─────────────────────────

    public function getJewWeightAttribute(): ?string
    {
        return $this->getEavAttribute('jew_weight');
    }

    public function getRingsizeAttribute(): ?string
    {
        return $this->getEavAttribute('ringsize');
    }

    public function getJewStonesAttribute(): ?string
    {
        return $this->getEavAttribute('jew_stones');
    }

    public function getPesoDiamanteAttribute(): ?string
    {
        return $this->getEavAttribute('peso_diamante');
    }

    public function getColoreStoneAttribute(): ?string
    {
        return $this->getEavAttribute('colore_stone');
    }

    public function getGoldLabelAttribute(): ?string
    {
        $optionId = $this->getEavAttribute('gold');

        return $this->getOptionLabel($optionId);
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

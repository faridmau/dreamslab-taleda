<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Trait HasMagentoEav
 *
 * Provides lazy-loading of EAV (Entity-Attribute-Value) attributes with per-entity caching.
 * Add this trait to any Magento-sourced model to access EAV attributes dynamically.
 *
 * @method string getEavAttribute(string $attributeCode, ?string $default = null)
 * @method array getAllEavAttributes()
 */
trait HasMagentoEav
{
    /**
     * Cache for EAV attributes per entity
     *
     * @var array<int, array<string, mixed>>
     */
    protected static array $eavCache = [];

    /**
     * Get a single EAV attribute value for this entity
     *
     * @param  string  $attributeCode  The attribute code (e.g., 'name', 'price')
     * @param  mixed  $default  Default value if attribute not found
     */
    public function getEavAttribute(string $attributeCode, mixed $default = null): mixed
    {
        $attributes = $this->getAllEavAttributes();

        return $attributes[$attributeCode] ?? $default;
    }

    /**
     * Get all EAV attributes for this entity, with caching
     *
     * @return array<string, mixed>
     */
    public function getAllEavAttributes(): array
    {
        $entityId = $this->getKey();
        $entityType = $this->getTable();

        // Return cached value if available
        $cacheKey = "{$entityType}:{$entityId}";
        if (isset(static::$eavCache[$cacheKey])) {
            return static::$eavCache[$cacheKey];
        }

        // Build attribute table name based on entity type
        $attributeTable = $this->buildAttributeTableName();

        // Query EAV attributes
        $attributes = DB::connection($this->getConnectionName())
            ->table($attributeTable)
            ->where('entity_id', $entityId)
            ->pluck('value', 'attribute_id')
            ->toArray();

        // Cache the result
        static::$eavCache[$cacheKey] = $attributes;

        return $attributes;
    }

    /**
     * Build the EAV attribute table name based on entity type
     */
    protected function buildAttributeTableName(): string
    {
        $table = $this->getTable();

        // Map entity tables to their EAV attribute table patterns
        $mapping = [
            'catalog_product_entity' => 'catalog_product_entity_varchar',
            'customer_entity' => 'customer_entity_varchar',
            // Add more mappings as needed
        ];

        return $mapping[$table] ?? "{$table}_varchar";
    }

    /**
     * Clear all cached EAV attributes (useful after updates or in testing)
     */
    public static function clearEavCache(): void
    {
        static::$eavCache = [];
    }

    /**
     * Clear cached EAV attributes for a specific entity
     */
    public static function clearEavCacheForEntity(int $entityId, ?string $entityType = null): void
    {
        $cacheKey = ($entityType ? "{$entityType}:{$entityId}" : "*:{$entityId}");

        foreach (static::$eavCache as $key => $value) {
            if ($entityType === null) {
                // If no entity type specified, match any with this entity ID
                if (str_ends_with($key, ":{$entityId}")) {
                    unset(static::$eavCache[$key]);
                }
            } elseif ($key === $cacheKey) {
                unset(static::$eavCache[$key]);
            }
        }
    }
}

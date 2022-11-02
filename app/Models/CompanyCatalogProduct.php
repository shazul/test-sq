<?php

namespace Pimeo\Models;

use Illuminate\Database\Eloquent\Collection;

/**
 * Pimeo\Models\CompanyCatalogProduct
 *
 * @property-read \Pimeo\Models\CompanyCatalog $companyCatalog
 * @property-read \Pimeo\Models\TechnicalProduct $technicalProduct
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\ChildProduct[] $childProducts
 * @mixin \Eloquent
 */
class CompanyCatalogProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_catalog_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_catalog_id',
        'technical_product_id',
    ];

    /**
     * Get the Catalogue the catalog product belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyCatalog()
    {
        return $this->belongsTo(CompanyCatalog::class);
    }

    /**
     * Get the Technical Product associated with this catalog product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function technicalProduct()
    {
        return $this->belongsTo(TechnicalProduct::class);
    }

    /**
     * The child products associated with the catalog.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childProducts()
    {
        return $this->hasMany(ChildProduct::class);
    }
}

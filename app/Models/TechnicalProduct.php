<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Pimeo\Models\TechnicalProduct
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\CompanyCatalogProduct[] $companyCatalogProducts
 * @mixin \Eloquent
 */
class TechnicalProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'technical_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_fr',
        'name_en',
        'original_attributes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'original_attributes' => 'json',
    ];

    /**
     * Get all catalog products associated with this technical product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyCatalogProducts()
    {
        return $this->hasMany(CompanyCatalogProduct::class);
    }
}

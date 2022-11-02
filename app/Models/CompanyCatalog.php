<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Pimeo\Models\CompanyCatalog
 *
 * @property integer $id
 * @property integer $company_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Pimeo\Models\Company $company
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\ChildProduct[] $childProducts
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyCatalog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyCatalog whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyCatalog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyCatalog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyCatalog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_catalogs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
    ];

    /**
     * Get the company the catalog belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all the child product associated with this catalog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childProducts()
    {
        return $this->hasMany(ChildProduct::class);
    }
}

<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Pimeo\Services\Fractal\Fractable;
use Pimeo\Repositories\Traits\LogActions;

/**
 * Pimeo\Models\ChildProduct
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $parent_product_id
 * @property integer $company_catalog_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $status
 * @property integer $created_by
 * @property integer $updated_by
 * @property-read \Pimeo\Models\Company $company
 * @property-read \Pimeo\Models\ParentProduct $parentProduct
 * @property-read \Pimeo\Models\CompanyCatalog $companyCatalog
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkAttribute[] $linkAttributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkMedia[] $mediaLinks
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereParentProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereCompanyCatalogId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ChildProduct whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class ChildProduct extends Attributable
{
    use Fractable, LogActions;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'child_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'parent_product_id',
        'company_catalog_id',
        'status',
    ];

    /**
     * Get the company the child product belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the parent associated with this child product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentProduct()
    {
        return $this->belongsTo(ParentProduct::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyCatalog()
    {
        return $this->belongsTo(CompanyCatalog::class);
    }

    /**
     * Return all attribute links for this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function linkAttributes()
    {
        return $this->morphMany(LinkAttribute::class, 'attributable')->orderBy('id', 'asc');
    }

    /**
     * Get the medias linked to this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function mediaLinks()
    {
        return $this->morphMany(LinkMedia::class, 'linkable');
    }

    /**
     * @param $id
     * @return bool
     */
    public function hasLinkAttribute($id)
    {
        $link_attribute = $this->linkAttributes()->where('link_attributes.attribute_id', $id)->get();
        return count($link_attribute) > 0;
    }

    public function isIndexable()
    {
        return ($this->status == AttributableModelStatus::PUBLISHED_STATUS &&
                $this->parentProduct->isIndexable() &&
                ! $this->mediaLinks->isEmpty());
    }

    public function getName($languageCode)
    {
        return LinkAttribute::join('attributes', 'attributes.id', '=', 'link_attributes.attribute_id')
                            ->where('attributes.name', 'child_product_name')
                            ->where('attributes.model_type', 'child_product')
                            ->where('link_attributes.attributable_id', $this->id)
            ->get(['link_attributes.values'])->first()->values[$languageCode];
    }
}

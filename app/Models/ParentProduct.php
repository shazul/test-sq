<?php

namespace Pimeo\Models;

use Pimeo\Services\Fractal\Fractable;
use Pimeo\Repositories\Traits\LogActions;

/**
 * Pimeo\Models\ParentProduct
 *
 * @property integer $id
 * @property integer $company_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $status
 * @property boolean $new_product
 * @property boolean $star_product
 * @property integer $nature_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property-read \Pimeo\Models\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkAttribute[] $linkAttributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkMedia[] $mediaLinks
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\ChildProduct[] $childProducts
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereNewProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereStarProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereNatureId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\ParentProduct whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class ParentProduct extends Attributable
{
    use Fractable, LogActions;

    protected $buildingComponentAttributeName = 'building_component';

    protected $functionAttributeName = 'product_function';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'parent_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'created_by',
        'updated_by',
        'status',
        'new_product',
        'star_product',
        'nature_id',
    ];

    /**
     * Get the company the parent product belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
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
     * The children of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childProducts()
    {
        return $this->hasMany(ChildProduct::class);
    }

    /**
     * Validate with a count if the parent has children in the published status.
     *
     * @return bool
     */
    public function isPublishable()
    {
        $count = $this->childProducts()
            ->where('child_products.status', AttributableModelStatus::PUBLISHED_STATUS)
            ->count();

        $is_publishable = false;
        if ($count > 0) {
            $is_publishable = true;
        }

        return $is_publishable;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function hasLinkAttribute($id)
    {
        $link_attribute = $this->linkAttributes()->where('link_attributes.attribute_id', $id)->get();

        return count($link_attribute) > 0;
    }

    public function isIndexable()
    {
        $isIndexable = false;

        if ($this->status == AttributableModelStatus::PUBLISHED_STATUS &&
            $this->isPublishable() && !$this->mediaLinks->isEmpty()
        ) {
            $isIndexable = true;
        }

        return $isIndexable;
    }

    public function getName($languageCode)
    {
        return LinkAttribute::join('attributes', 'attributes.id', '=', 'link_attributes.attribute_id')
            ->where('attributes.name', 'name')
            ->where('attributes.model_type', 'parent_product')
            ->where('link_attributes.attributable_id', $this->id)
            ->get(['link_attributes.values'])->first()->values[$languageCode];
    }
}

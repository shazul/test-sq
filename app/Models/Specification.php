<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Pimeo\Services\Fractal\Fractable;
use Pimeo\Repositories\Traits\LogActions;

/**
 * Pimeo\Models\Specification
 *
 * @property integer $id
 * @property integer $company_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $status
 * @property integer $created_by
 * @property integer $updated_by
 * @property-read \Pimeo\Models\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkAttribute[] $linkAttributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkMedia[] $mediaLinks
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Specification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Specification whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Specification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Specification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Specification whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Specification whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Specification whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Specification extends Attributable
{
    use Fractable, LogActions;

    protected $buildingComponentAttributeName = 'spec_building_component';

    protected $functionAttributeName = 'spec_function';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'specifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the company associated with the specification belongs to.
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
        return ($this->status == AttributableModelStatus::COMPLETE_STATUS && ! $this->mediaLinks->isEmpty());
    }

    public function getName($languageCode)
    {
        return LinkAttribute::join('attributes', 'attributes.id', '=', 'link_attributes.attribute_id')
                            ->where('attributes.name', 'spec_name')
                            ->where('attributes.model_type', 'specification')
                            ->where('link_attributes.attributable_id', $this->id)
            ->get(['link_attributes.values'])->first()->values[$languageCode];
    }
}

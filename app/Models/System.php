<?php

namespace Pimeo\Models;

use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Support\Facades\App;
use Pimeo\Services\Fractal\Fractable;
use Pimeo\Repositories\Traits\LogActions;

/**
 * Pimeo\Models\System
 *
 * @property integer $id
 * @property integer $company_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $status
 * @property boolean $is_starred
 * @property integer $created_by
 * @property integer $updated_by
 * @property-read \Pimeo\Models\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkAttribute[] $linkAttributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LayerGroup[] $layerGroups
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkMedia[] $mediaLinks
 * @property mixed buildingComponents
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\System whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\System whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\System whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\System whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\System whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\System whereIsStarred($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\System whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\System whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class System extends Attributable
{
    use Fractable, LogActions;

    protected $functionAttributeName = 'system_function';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'systems';

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
        'is_starred',
    ];

    /**
     * Get the company the system belongs to.
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
     * Return all layers groups linked to a system
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function layerGroups()
    {
        return $this->hasMany(LayerGroup::class);
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
        return ($this->status == AttributableModelStatus::COMPLETE_STATUS && ! $this->mediaLinks->isEmpty());
    }

    public function getName($languageCode)
    {
        return LinkAttribute::join('attributes', 'attributes.id', '=', 'link_attributes.attribute_id')
                            ->where('attributes.name', 'system_name')
                            ->where('attributes.model_type', 'system')
                            ->where('link_attributes.attributable_id', $this->id)
            ->get(['link_attributes.values'])->first()->values[$languageCode];
    }

    public function getAttributeByName($name, $langCode)
    {
        $attributes = $this->linkAttributes->keyBy('attribute.name')->all();

        if (! isset($attributes[$name])) {
            return null;
        }

        $values = $attributes[$name]->values;

        if (! is_array($values) || ! isset($values[$langCode])) {
            return null;
        }

        return $attributes[$name]->values;
    }

    public function buildingComponents()
    {
        return $this->belongsToMany(BuildingComponent::class, 'system_building_component');
    }

    public function getBuildingComponentsInlineTitles()
    {
        return $this->buildingComponents->map(function ($item, $key) {
            return trans('building-component.component.' . $item->code);
        })->implode(', ');
    }

    public function getBuildingComponentIdsAttribute()
    {
        return $this->buildingComponents()->get(['id'])->pluck('id')->toArray();
    }

    /**
     * @param $languageCode
     * @param $companyId
     *
     * @return mixed
     */
    public function getBuildingComponents($languageCode, $companyId)
    {
        /** @var Collection $buildingComponents */
        $buildingComponents = $this->buildingComponents->pluck('code');
        $locale = App::getLocale();
        App::setLocale($languageCode);
        $collection = $buildingComponents->map(function ($item, $key) {
            return trans('building-component.component.' . $item);
        });
        App::setLocale($locale);

        return $collection;
    }
}

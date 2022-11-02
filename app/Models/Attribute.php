<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Pimeo\Repositories\Traits\LogActions;

/**
 * Pimeo\Models\Attribute
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $attribute_type_id
 * @property integer $attribute_label_id
 * @property string $name
 * @property string $model_type
 * @property boolean $has_value
 * @property boolean $is_min_requirement
 * @property boolean $is_parent_attribute
 * @property string $options
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $attribute_group_id
 * @property boolean $should_index
 * @property integer $created_by
 * @property integer $updated_by
 * @property-read \Pimeo\Models\AttributeLabel $label
 * @property-read \Pimeo\Models\AttributeType $type
 * @property-read \Pimeo\Models\Company $company
 * @property-read \Pimeo\Models\AttributeValue $value
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkAttribute[] $attributeLinks
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Nature[] $natures
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereAttributeTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereAttributeLabelId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereModelType($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereHasValue($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereIsMinRequirement($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereIsParentAttribute($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereOptions($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereAttributeGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereShouldIndex($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Attribute whereUpdatedBy($value)
 * @method static \Pimeo\Models\Attribute updateOrCreate(array $attributes, array $values = [])
 * @mixin \Eloquent
 */
class Attribute extends Model
{
    use LogActions;

    const MODEL_TYPE_PARENT_PRODUCT = 'parent_product';
    const MODEL_TYPE_CHILD_PRODUCT = 'child_product';
    const MODEL_TYPE_PRODUCT = 'product';
    const MODEL_TYPE_SPECIFICATION = 'specification';
    const MODEL_TYPE_DETAIL = 'detail';
    const MODEL_TYPE_SYSTEM = 'system';
    const MODEL_TYPE_TECHNICAL_BULLETIN = 'technical_bulletin';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_type',
        'attribute_nature',
        'company_id',
        'has_value',
        'name',
        'is_parent_attribute',
        'is_min_requirement',
        'attribute_type_id',
        'attribute_label_id',
        'options',
        'should_index',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_min_requirement'  => 'bool',
        'has_value'           => 'bool',
        'is_parent_attribute' => 'bool',
        'should_index'        => 'bool',
        'options'             => 'json',
    ];


    /**
     * Return true if the attributes has values.
     *
     * @return bool
     */
    public function hasValue()
    {
        return $this->has_value;
    }

    /**
     * Return the type of model this attribute is assoc with.
     *
     * @return string
     */
    public function getModelType()
    {
        return $this->model_type;
    }

    /**
     * Get the Label associated with the attribute.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function label()
    {
        return $this->belongsTo(AttributeLabel::class, 'attribute_label_id');
    }

    /**
     * Get the Type associated with the attribute.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(AttributeType::class, 'attribute_type_id');
    }

    /**
     * Get the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the Value is associated with the attribute.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function value()
    {
        return $this->hasOne(AttributeValue::class);
    }

    /**
     * Get all the attribute links this attribute belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function attributeLinks()
    {
        return $this->hasMany(LinkAttribute::class);
    }

    /**
     * The natures associated to the attribute.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function natures()
    {
        return $this->belongsToMany(Nature::class);
    }

    public function buildingComponents()
    {
        return $this->belongsToMany(BuildingComponent::class);
    }

    public function getIndexKeyAttribute()
    {
        $indexKey = $this->attributes['name'];

        $options = json_decode($this->attributes['options'], true);

        $specialIndexKey = array_get($options, 'special_index_key');
        if ($specialIndexKey) {
            $indexKey = $specialIndexKey;
        }

        return $indexKey;
    }
}

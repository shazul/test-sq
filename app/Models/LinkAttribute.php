<?php

namespace Pimeo\Models;

/**
 * Pimeo\Models\LinkAttribute
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property integer $attributable_id
 * @property string $attributable_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property array $values
 * @property-read \Pimeo\Models\Attribute $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $attributable
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkAttribute whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkAttribute whereAttributeId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkAttribute whereAttributableId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkAttribute whereAttributableType($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkAttribute whereValues($value)
 * @mixin \Eloquent
 */
class LinkAttribute extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'link_attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attributable_id',
        'attributable_type',
        'attribute_id',
        'values',
    ];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = [
        'attributable',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'values' => 'json',
    ];

    /**
     * Return the attribute associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Enables the morph property to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attributable()
    {
        return $this->morphTo();
    }
}

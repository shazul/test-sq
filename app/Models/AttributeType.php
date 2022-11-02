<?php

namespace Pimeo\Models;

use Carbon\Carbon;

/**
 * Pimeo\Models\AttributeType
 *
 * @property integer $id
 * @property string $name
 * @property boolean $public
 * @property string $code
 * @property string $specs
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Attribute[] $attribute
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeType wherePublic($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeType whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeType whereSpecs($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeType whereUpdatedAt($value)
 * @method static \Pimeo\Models\AttributeType updateOrCreate(array $attributes, array $values = [])
 * @mixin \Eloquent
 */
class AttributeType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'public',
        'specs',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'public' => 'bool',
        'specs'  => 'json',
    ];

    /**
     * Get the Attribute associated with the type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attribute()
    {
        return $this->hasMany(Attribute::class);
    }
}

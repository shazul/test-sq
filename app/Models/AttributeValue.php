<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Exception;

/**
 * Pimeo\Models\AttributeValue
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property array $values
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Pimeo\Models\Attribute $attribute
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeValue whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeValue whereValues($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeValue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AttributeValue extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_values';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attribute_id',
        'values',
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
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = [
        'attribute',
    ];

    /**
     * Get the Attribute associated with the value.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Set the values in the JSON for the language
     *
     * @param string       $lang_code Language code
     * @param array|string $values    Values
     */
    public function setValuesForLanguageCode($lang_code, $values)
    {
        $this->values[$lang_code] = $values;
    }

    /**
     * Return the values for a specific language code.
     *
     * @param $lang_code
     * @return mixed
     * @throws Exception
     */
    public function valuesByLangCode($lang_code)
    {
        if (!isset($this->values[$lang_code])) {
            throw new Exception('Invalid language code');
        }

        return $this->values[$lang_code];
    }

    public function countValues()
    {
        $count = 0;

        foreach ($this->values as $value) {
            $count = max($count, count($value));
        }

        return $count;
    }
}

<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Laracasts\Presenter\PresentableTrait;
use Pimeo\Services\Presenters\AttributeLabelPresenter;

/**
 * Pimeo\Models\AttributeLabel
 *
 * @property integer $id
 * @property string $name
 * @property string $values
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Pimeo\Models\Attribute $attribute
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeLabel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeLabel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeLabel whereValues($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeLabel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\AttributeLabel whereUpdatedAt($value)
 * @method static \Pimeo\Models\AttributeLabel updateOrCreate(array $attributes, array $values = [])
 * @mixin \Eloquent
 */
class AttributeLabel extends Model
{
    use PresentableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_labels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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

    protected $presenter = AttributeLabelPresenter::class;

    /**
     * Get the Attribute associated with the label.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function attribute()
    {
        return $this->hasOne(Attribute::class);
    }

    public function getValue($language)
    {
        return array_get($this->values, $language);
    }

    public function getValues()
    {
        $values = [];
        $keys = array_keys($this->values);

        foreach ($keys as $key) {
            $values[] = [
                'language' => $key,
                'value'    => $this->values[$key],
            ];
        }

        return $values;
    }
}

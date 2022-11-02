<?php

namespace Pimeo\Models;

use Illuminate\Database\Eloquent\Collection;

/**
 * Pimeo\Models\Nature
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Attribute[] $attributes
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Nature whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Nature whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Nature whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Nature whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Nature whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Nature extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'natures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * The attributes associated to the nature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }
}

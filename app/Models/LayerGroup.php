<?php

namespace Pimeo\Models;

/**
 * Pimeo\Models\LayerGroup
 *
 * @property integer $id
 * @property integer $system_id
 * @property string $name
 * @property integer $position
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Layer[] $layers
 * @property-read \Pimeo\Models\System $system
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LayerGroup whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LayerGroup whereSystemId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LayerGroup whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LayerGroup wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LayerGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LayerGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LayerGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'layer_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'system_id',
        'name',
        'position',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'json',
    ];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = [
        'system',
    ];

    /**
     * Return all layers linked to a layer group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function layers()
    {
        return $this->hasMany(Layer::class);
    }

    /**
     * The system to which belong the layer_group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function system()
    {
        return $this->belongsTo(System::class);
    }
}

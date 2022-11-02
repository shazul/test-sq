<?php

namespace Pimeo\Models;

use Illuminate\Database\Eloquent\Collection;

/**
 * Pimeo\Models\Layer
 *
 * @property integer $id
 * @property integer $layer_group_id
 * @property integer $parent_product_id
 * @property string $product_name
 * @property string $product_function
 * @property integer $position
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Pimeo\Models\LayerGroup $layerGroup
 * @property-read \Pimeo\Models\ParentProduct $parentProduct
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Layer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Layer whereLayerGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Layer whereParentProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Layer whereProductName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Layer whereProductFunction($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Layer wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Layer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Layer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Layer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'layers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'layer_group_id',
        'parent_product_id',
        'product_name',
        'product_function',
        'position',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_name'     => 'json',
        'product_function' => 'json',
    ];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = [
        'layerGroup',
        'parentProduct',
    ];

    /**
     * The layer_group to which belong the layer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function layerGroup()
    {
        return $this->belongsTo(LayerGroup::class);
    }

    /**
     * The parent_product which is used by the layer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentProduct()
    {
        return $this->belongsTo(ParentProduct::class);
    }
}

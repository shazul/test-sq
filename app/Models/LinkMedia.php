<?php

namespace Pimeo\Models;

use Carbon\Carbon;

/**
 * Pimeo\Models\LinkMedia
 *
 * @property integer $id
 * @property integer $media_id
 * @property integer $linkable_id
 * @property string $linkable_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Pimeo\Models\Media $media
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $linkable
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkMedia whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkMedia whereMediaId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkMedia whereLinkableId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkMedia whereLinkableType($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\LinkMedia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LinkMedia extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'link_medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'media_id',
        'linkable_id',
        'linkable_type',
    ];

    /**
     * Get the media associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Enables the morph property to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function linkable()
    {
        return $this->morphTo();
    }
}

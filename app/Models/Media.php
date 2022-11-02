<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Pimeo\Services\Fractal\Fractable;
use Pimeo\Services\Fractal\FractalCollection;

/**
 * Pimeo\Models\Media
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\LinkMedia[] $linkMedias
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\Company[] $companies
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Media whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Media whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Media whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Media whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Media extends Model
{
    use Fractable;

    /**
     * Code for the website media
     */
    const CODE_WEBSITE = 'website';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The linked medias associated with the media.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function linkMedias()
    {
        return $this->hasMany(LinkMedia::class);
    }

    /**
     * The companies related to the media.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
}

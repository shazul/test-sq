<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Pimeo\Services\Fractal\Contracts\Fractable as FractableContract;
use Pimeo\Services\Fractal\Fractable;

/**
 * Pimeo\Models\Language
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\User[] $users
 * @property-read mixed $iso_code
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Language whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Language whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Language whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Language whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Language extends Model implements FractableContract
{
    use Fractable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'iso_code',
    ];

    /**
     * Get the Language associated with this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return string
     */
    public function getIsoCodeAttribute()
    {
        return substr($this->code, 0, 2);
    }
}

<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Pimeo\Models\Group
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Group whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    const SUPER_ADMIN_CODE = 'super_admin';
    const ADMIN_CODE = 'admin';
    const EDITOR_CODE = 'editor';
    const USER_CODE = 'user';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'groups';

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
     * Get the Users associated with the group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

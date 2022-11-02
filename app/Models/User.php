<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Pimeo\Repositories\Traits\LogActions;

/**
 * Pimeo\Models\User
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property boolean $active
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property integer $active_language_id
 * @property integer $active_company_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\Company[] $companies
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Group[] $groups
 * @property-read \Pimeo\Models\Language $activeLanguage
 * @property-read \Pimeo\Models\Company $activeCompany
 * @property-read mixed $full_name
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereActiveLanguageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereActiveCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\User whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes, LogActions;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'active',
        'email',
        'password',
        'active_language_id',
        'active_company_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'bool',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the companies the user is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    /**
     * Get the Groups the user is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Check if current user belongs to SuperAdmin Group.
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(Group::SUPER_ADMIN_CODE);
    }

    /**
     * Check if current user belongs to Admin Group.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasRole(Group::ADMIN_CODE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activeLanguage()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activeCompany()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check if current user belongs to the given Group.
     *
     * @param  string|array
     *
     * @return boolean
     */
    public function hasRole($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $userGroupCodes = $this->groups->pluck('code');

        return $userGroupCodes->intersect($roles)->count() > 0;
    }

    public function getLanguage()
    {
        static $language = null;

        if ($language === null) {
            if ($this->activeLanguage) {
                $language = $this->activeLanguage;
            } else {
                $language = $this->getCompany()->defaultLanguage;
            }
        }

        return $language;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return String
     */
    public function getLanguageIsoCode()
    {
        return $this->getLanguage()->iso_code;
    }

    public function getLanguageCode()
    {
        return $this->getLanguage()->code;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        static $company = null;
        if ($company === null) {
            if ($this->activeCompany) {
                $company = $this->activeCompany;
            } else {
                $company = $this->companies->first();
                if (is_null($company)) {
                    throw new \LogicException('A company must be set in this user.');
                } else {
                    $this->active_company_id = $company->id;
                    $this->save();
                }
            }
        }

        return $company;
    }
}

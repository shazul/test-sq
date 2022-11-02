<?php

namespace Pimeo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Pimeo\Services\Fractal\Fractable;
use Pimeo\Repositories\Traits\LogActions;

/**
 * Pimeo\Models\Company
 *
 * @property integer $id
 * @property string $name
 * @property integer $default_language_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property-read \Pimeo\Models\Language $defaultLanguage
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\Language[] $languages
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\Specification[] $specifications
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\TechnicalBulletin[] $technicalBulletins
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\ParentProduct[] $parentProducts
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\ChildProduct[] $childProducts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\User[] $users
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\Detail[] $details
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Attribute[] $attributes
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\System[] $systems
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\CompanyCatalog[] $companyCatalogs
 * @property-read \Pimeo\Services\Fractal\FractalCollection|\Pimeo\Models\Media[] $medias
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Company whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Company whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Company whereDefaultLanguageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Company whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\Company whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    use Fractable, LogActions;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'default_language_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the default language of the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultLanguage()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     *  Get the company languages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    /**
     * Get the Devis associated with the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specifications()
    {
        return $this->hasMany(Specification::class);
    }

    /**
     * Get the technical bulletins associated with the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function technicalBulletins()
    {
        return $this->hasMany(TechnicalBulletin::class);
    }

    /**
     * Get the ParentProducts associated with the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parentProducts()
    {
        return $this->hasMany(ParentProduct::class);
    }

    /**
     * Get the ChildProducts associated with the company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childProducts()
    {
        return $this->hasMany(ChildProduct::class);
    }

    /**
     * Get the Users associated with the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the Details associated with the company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(Detail::class);
    }

    /**
     * Get the Attributes associated with the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

    /**
     * Get the Systems associated with the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function systems()
    {
        return $this->hasMany(System::class);
    }

    /**
     * Get the company catalogs associated with this company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyCatalogs()
    {
        return $this->hasMany(CompanyCatalog::class);
    }

    /**
     * The medias related to the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function medias()
    {
        return $this->belongsToMany(Media::class);
    }


    public function isDeletable()
    {
        $canDelete = false;
        if ($this->hasUserActive() == false) {
            $canDelete = true;
        }

        return $canDelete;
    }

    public function hasUserActive()
    {
        $hasUser = false;
        if (DB::table('users')->where('active_company_id', $this->id)->count() > 0) {
            $hasUser = true;
        }

        return $hasUser;
    }
}

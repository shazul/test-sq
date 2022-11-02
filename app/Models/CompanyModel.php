<?php

namespace Pimeo\Models;

/**
 * Pimeo\Models\CompanyModel
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $model
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyModel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyModel whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyModel whereModel($value)
 * @mixin \Eloquent
 */
class CompanyModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_model';

    /**
     * @var bool
     */
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model',
        'company_id',
    ];
}

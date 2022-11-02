<?php

namespace Pimeo\Models;

/**
 * Pimeo\Models\CompanyBlog
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $language_id
 * @property integer $blog_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Pimeo\Models\Company $company
 * @property-read \Pimeo\Models\Language $language
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyBlog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyBlog whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyBlog whereLanguageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyBlog whereBlogId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyBlog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pimeo\Models\CompanyBlog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyBlog extends Model
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}

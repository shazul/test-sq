<?php

namespace Pimeo\Repositories;

use Pimeo\Events\Pim\CompanyWasCreated;
use Pimeo\Events\Pim\CompanyWasDeleted;
use Pimeo\Events\Pim\CompanyWasUpdated;
use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeLabel;
use Pimeo\Models\AttributeValue;
use Pimeo\Models\BaseAttributes;
use Pimeo\Models\Company;
use Pimeo\Models\CompanyCatalog;
use Pimeo\Models\CompanyModel;
use Pimeo\Models\Media;
use Pimeo\Models\User;

class CompanyRepository
{
    /**
     * Create a new Company.
     *
     * @param  array $attributes
     * @return \Pimeo\Models\Company
     */
    public function create(array $attributes = [])
    {
        $company = Company::create($attributes);

        $company->languages()->sync($attributes['languages']);

        event(new CompanyWasCreated($company->fresh()));

        $this->giveAccessToSuperadmins($company->id);

        $mediaWebsite = Media::whereCode('website')->first();
        $company->medias()->attach($mediaWebsite);

        $companyIndexer = new CompanyIndexer();
        $companyIndexer->indexCompany($company);

        $companyCatalogue = new CompanyCatalog();
        $companyCatalogue->company()->associate($company);
        $companyCatalogue->save();

        return $company;
    }

    /**
     * Update the Company with the given attributes.
     *
     * @param  int   $id
     * @param  array $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        $company = Company::find($id);
        $company->fill($attributes);
        $company->save();

        event(new CompanyWasUpdated($company->fresh()));

        return $company;
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        $company = Company::find($id);

        $company->users()->detach();

        $companyIndexer = new CompanyIndexer();
        $companyIndexer->deleteIndex($company);
        CompanyModel::whereCompanyId($id)->delete();

        $deleted = Company::destroy($id);

        if ($deleted) {
            event(new CompanyWasDeleted($company));
        }

        return $deleted;
    }

    /**
     * Donnes accès à tous les superadmins à une compagnie
     * @param  int $company_id
     * @return void
     */
    public function giveAccessToSuperadmins($company_id)
    {
        $superadmins = User::whereHas('groups', function ($query) {
            $query->whereCode('super_admin');
        })->get();
        $superadmins->each(function ($superadmin) use ($company_id) {
            $superadmin->companies()->attach($company_id);
        });
    }

    /**
     * Ajoute les attributs minimaux à une compagnie
     * @param Company $company
     */
    public function addBaseAttributes($company)
    {
        $attributes = BaseAttributes::getArray();

        foreach ($attributes as $attribute) {
            foreach ($company->languages as $language) {
                $attribute['label']['values'][$language->code] = $attribute['label']['values']['en'];
            }
            $attributeLabel = AttributeLabel::create($attribute['label']);

            $attribute['attribute']['attribute_label_id'] = $attributeLabel->id;
            $attribute['attribute']['company_id'] = $company->id;

            $newAttribute = Attribute::create($attribute['attribute']);

            if (isset($attribute['value'])) {
                $attribute['value']['attribute_id'] = $newAttribute['id'];
                foreach ($company->languages as $language) {
                    $attribute['value']['values'][$language->code] = $attribute['value']['values']['en'];
                }
                AttributeValue::create($attribute['value']);
            }

            if (isset($attribute['natures'])) {
                $newAttribute->natures()->sync($attribute['natures']);
            }
        }
    }
}

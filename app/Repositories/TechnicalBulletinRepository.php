<?php

namespace Pimeo\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Pimeo\Models\TechnicalBulletin;
use Pimeo\Repositories\Traits\Decodable;
use Pimeo\Repositories\Traits\QueryableFields;
use Pimeo\Repositories\Traits\Sortable;

class TechnicalBulletinRepository
{
    use Decodable, QueryableFields, Sortable;

    /**
     * Get all instance of TechnicalBulletin.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\TechnicalBulletin[]
     */
    public function all()
    {
        return TechnicalBulletin::whereCompanyId(current_company()->id)->paginate();
    }

    /**
     * Get all instance of TechnicalBulletin including name and building_component values.
     *
     * @param string $status
     * @param string|null $sorting
     * @param string|null $langCode
     *
     * @return Collection|\Pimeo\Models\TechnicalBulletin[]
     */
    public function allForListing($status = '*', $sorting = null, $langCode = null)
    {
        $technicalBulletinsList = $this->getQueryForFields(
            'technical_bulletins',
            TechnicalBulletin::class,
            ['technical_bulletin_name', 'technical_bulletin_building_component']
        );

        if ($status != '*') {
            $technicalBulletinsList->where('technical_bulletins.status', $status);
        }

        $technicalBulletinsList = $technicalBulletinsList
            ->where('technical_bulletins.company_id', current_company()->id)
            ->get();

        $technicalBulletins = [];
        foreach ($technicalBulletinsList as $bulletin) {
            $technicalBulletins[$bulletin->attributable_id][$bulletin->name] = $this->decodeValues($bulletin);
            if ($bulletin->media_name != null) {
                $technicalBulletins[$bulletin->attributable_id]['media_name'][$bulletin->media_name] = $bulletin
                    ->media_name;
            }
        }

        if (!is_null($sorting)) {
            $technicalBulletins = $this->sortResults($sorting, $technicalBulletins, $langCode);
        }

        return $technicalBulletins;
    }

    /**
     * Find an instance of TechnicalBulletin with the given ID.
     *
     * @param  int $id
     * @return \Pimeo\Models\TechnicalBulletin
     */
    public function find($id)
    {
        return TechnicalBulletin::find($id);
    }

    /**
     * Create a new instance of TechnicalBulletin.
     *
     * @param  array $attributes
     * @return \Pimeo\Models\TechnicalBulletin
     */
    public function create(array $attributes = [])
    {
        return TechnicalBulletin::create($attributes);
    }

    /**
     * Update the TechnicalBulletin with the given attributes.
     *
     * @param  int   $id
     * @param  array $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return TechnicalBulletin::find($id)->update($attributes);
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
        return TechnicalBulletin::find($id)->delete();
    }
}

<?php

namespace Pimeo\Repositories;

use Pimeo\Models\Attribute;
use Pimeo\Models\System;
use Pimeo\Repositories\Traits\Decodable;
use Pimeo\Repositories\Traits\QueryableFields;
use Pimeo\Repositories\Traits\Sortable;

class SystemRepository
{
    use Decodable, QueryableFields, Sortable;

    /**
     * Get all instance of System.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\System[]
     */
    public function all()
    {
        return System::whereCompanyId(current_company()->id)->paginate();
    }

    /**
     * Get all instance of System for the given System Type code.
     *
     * @param string $status
     * @param null $sorting
     * @param null $langCode
     * @param null $search
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\System[]
     */
    public function allForListing($status, $sorting = null, $langCode = null, $search = null)
    {
        $systems = [];

        $systemsList = $this->getQueryForFields(
            'systems',
            System::class,
            ['system_name'],
            ['building_components.code AS building_component']
        );

        if ($status != '*') {
            $systemsList->where('systems.status', $status);
        }

        $systemsList = $systemsList->where('systems.company_id', current_company()->id)->get();

        foreach ($systemsList as $system) {
            $systems[$system->attributable_id][$system->name] = $this->decodeValues($system);
            if ($system->media_name != null) {
                $systems[$system->attributable_id]['media_name'][$system->media_name] = $system->media_name;
            }
            if (property_exists($system, 'building_component') && ! empty($system->building_component)) {
                $systems[$system->attributable_id]['building_component'][$system->building_component] =
                    trans('building-component.component.' . $system->building_component);
            }
        }

        if (! is_null($sorting)) {
            $systems = $this->sortResults($sorting, $systems, $langCode);
        }

        return $systems;
    }

    /**
     * Find an instance of System with the given ID.
     *
     * @param  int $id
     *
     * @return \Pimeo\Models\System
     */
    public function find($id)
    {
        return System::find($id);
    }

    /**
     * Create a new instance of System.
     *
     * @param  array $attributes
     *
     * @return \Pimeo\Models\System
     */
    public function create(array $attributes = [])
    {
        return System::create($attributes);
    }

    /**
     * Update the System with the given attributes.
     *
     * @param  int $id
     * @param  array $attributes
     *
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return System::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return System::find($id)->delete();
    }
}

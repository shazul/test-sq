<?php

namespace Pimeo\Repositories;

use Pimeo\Models\Specification;
use Pimeo\Repositories\Traits\Decodable;
use Pimeo\Repositories\Traits\QueryableFields;
use Pimeo\Repositories\Traits\Sortable;

class SpecificationRepository
{
    use Decodable, QueryableFields, Sortable;

    /**
     * Get all instance of Specification.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Specification[]
     */
    public function all()
    {
        return Specification::whereCompanyId(current_company()->id)->paginate();
    }

    /**
     * Get all instance of Specification including name and building_component values.
     *
     * @param string $status
     * @param string|null $sorting
     * @param string|null $langCode
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Specification[]
     */
    public function allForListing($status = '*', $sorting = null, $langCode = null)
    {
        $specificationsList = $this->getQueryForFields(
            'specifications',
            Specification::class,
            ['spec_name', 'spec_building_component']
        );

        if ($status != '*') {
            $specificationsList->where('specifications.status', $status);
        }

        $specificationsList = $specificationsList->where('specifications.company_id', current_company()->id)->get();

        $specifications = [];
        foreach ($specificationsList as $spec) {
            $specifications[$spec->attributable_id][$spec->name] = $this->decodeValues($spec);
            if ($spec->media_name != null) {
                $specifications[$spec->attributable_id]['media_name'][$spec->media_name] = $spec->media_name;
            }
        }

        if (!is_null($sorting)) {
            $specifications = $this->sortResults($sorting, $specifications, $langCode);
        }

        return $specifications;
    }

    /**
     * Find an instance of Specification with the given ID.
     *
     * @param  int $id
     * @return \Pimeo\Models\Specification
     */
    public function find($id)
    {
        return Specification::whereCompanyId(current_company()->id)->find($id);
    }

    /**
     * Create a new instance of Specification.
     *
     * @param  array $attributes
     * @return \Pimeo\Models\Specification
     */
    public function create(array $attributes = [])
    {
        return Specification::create($attributes);
    }

    /**
     * Update the Specification with the given attributes.
     *
     * @param  int   $id
     * @param  array $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        $attributes['updated_by'] = auth()->user()->id;
        return Specification::find($id)->update($attributes);
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
        return Specification::find($id)->delete();
    }
}

<?php

namespace Pimeo\Repositories;

use Pimeo\Models\Detail;
use Pimeo\Repositories\Traits\Decodable;
use Pimeo\Repositories\Traits\QueryableFields;
use Pimeo\Repositories\Traits\Sortable;

class DetailRepository
{
    use Decodable, QueryableFields, Sortable;

    /**
     * Get all instance of Detail.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Detail[]
     */
    public function all()
    {
        return Detail::whereCompanyId(current_company()->id)->paginate();
    }

    /**
     * Get all instance of Detail including name and building_component values.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Detail[]
     */
    public function allForListing($status = '*', $sorting = null, $langCode = null)
    {
        $detailsList = $this->getQueryForFields(
            'details',
            Detail::class,
            ['detail_name', 'detail_building_component']
        );

        if ($status != '*') {
            $detailsList->where('details.status', $status);
        }

        $detailsList = $detailsList->where('details.company_id', current_company()->id)->get();

        $details = [];

        if ($detailsList) {
            foreach ($detailsList as $detail) {
                $details[$detail->attributable_id][$detail->name] = $this->decodeValues($detail);
                if ($detail->media_name != null) {
                    $details[$detail->attributable_id]['media_name'][$detail->media_name] = $detail->media_name;
                }
            }

            if (!is_null($sorting)) {
                $details = $this->sortResults($sorting, $details, $langCode);
            }
        }

        return $details;
    }

    /**
     * Find an instance of Detail with the given ID.
     *
     * @param  int $id
     * @return \Pimeo\Models\Detail
     */
    public function find($id)
    {
        return Detail::whereCompanyId(current_company()->id)->find($id);
    }

    /**
     * Create a new instance of Detail.
     *
     * @param  array $attributes
     * @return \Pimeo\Models\Detail
     */
    public function create(array $attributes = [])
    {
        return Detail::create($attributes);
    }

    /**
     * Update the Detail with the given attributes.
     *
     * @param  int   $id
     * @param  array $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        $attributes['updated_by'] = auth()->user()->id;
        return Detail::find($id)->update($attributes);
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
        return Detail::find($id)->delete();
    }
}

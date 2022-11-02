<?php

namespace Pimeo\Repositories\Traits;

use DB;

trait QueryableFields
{
    /**
     * @param  string $table
     * @param  string $className
     * @param  array $fields
     * @param  array $addSelect
     * @param  bool $joinValues
     * @return Builder
     */
    protected function getQueryForFields($table, $className, array $fields, array $addSelect = [], $joinValues = true)
    {
        $select = array_merge($addSelect, [
            "a.name",
            'link_attributes.values',
            'attributable_id',
            'medias.name AS media_name'
        ]);
        if ($joinValues) {
            $select[] = 'attribute_values.values as attribute_values';
        }
        $query = DB::table($table)->select($select)
            ->join('link_attributes', 'link_attributes.attributable_id', '=', $table . '.id')
            ->join('attributes AS a', 'a.id', '=', 'link_attributes.attribute_id')
            ->leftJoin('link_medias', function ($join) use ($table, $className) {
                $join->on('link_medias.linkable_id', '=', $table . '.id')
                    ->where('link_medias.linkable_type', '=', $className);
            })
            ->leftJoin('medias', 'link_medias.media_id', '=', 'medias.id');

        if ($table == 'systems') {
            $query->leftJoin('system_building_component', 'system_building_component.system_id', '=', $table . '.id')
                  ->leftJoin('building_components', function ($join) use ($table) {
                      $join->on('system_building_component.building_component_id', '=', 'building_components.id');
                  });
        }

        if ($joinValues) {
            $query->leftJoin('attribute_values', 'a.id', '=', 'attribute_values.attribute_id');
        }

        $query->where('link_attributes.attributable_type', $className)
            ->whereIn('a.name', $fields);

        return $query;
    }
}

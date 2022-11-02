<?php

namespace Pimeo\Repositories;

use Pimeo\Models\AttributeType;

class AttributeTypeRepository
{
    /**
     * Get all instance of AttributeType.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\AttributeType[]
     */
    public function all(array $columns = [])
    {
        return AttributeType::all($columns);
    }

    /**
     * Find an instance of AttributeType with the given ID.
     *
     * @param  int  $id
     * @return \Pimeo\Models\AttributeType
     */
    public function find($id)
    {
        return AttributeType::find($id);
    }

    /**
     * Create a new instance of AttributeType.
     *
     * @param  array  $attributes
     * @return \Pimeo\Models\AttributeType
     */
    public function create(array $attributes = [])
    {
        return AttributeType::create($attributes);
    }

    /**
     * Update the AttributeType with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return AttributeType::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int  $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return AttributeType::find($id)->delete();
    }

    /**
     * Get the public attribute types for the given model type.
     *
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\AttributeType[]
     */
    public function getAllPublic(array $columns = [])
    {
        return AttributeType::wherePublic(true)->get($columns);
    }

    /**
     * Find an instance of the attribute type by its code.
     *
     * @param  string $code
     * @return \Pimeo\Models\AttributeType
     */
    public function findByCode($code)
    {
        return AttributeType::whereCode($code)->first();
    }
}

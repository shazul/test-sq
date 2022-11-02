<?php

namespace Pimeo\Repositories;

use Pimeo\Models\AttributeValue;

class AttributeValueRepository
{
    /**
     * Get all instance of AttributeValue.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\AttributeValue[]
     */
    public function all()
    {
        return AttributeValue::all();
    }

    /**
     * Find an instance of AttributeValue with the given ID.
     *
     * @param  int $id
     * @return \Pimeo\Models\AttributeValue
     */
    public function find($id)
    {
        return AttributeValue::find($id);
    }

    /**
     * Create a new instance of AttributeValue.
     *
     * @param  array $attributes
     * @return \Pimeo\Models\AttributeValue
     */
    public function create(array $attributes = [])
    {
        return AttributeValue::create($attributes);
    }

    /**
     * Update the AttributeValue with the given attributes.
     *
     * @param  int   $id
     * @param  array $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return AttributeValue::find($id)->update($attributes);
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
        return AttributeValue::find($id)->delete();
    }
}

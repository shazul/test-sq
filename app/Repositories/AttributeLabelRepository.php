<?php

namespace Pimeo\Repositories;

use Pimeo\Models\AttributeLabel;

class AttributeLabelRepository
{
    /**
     * Get all instance of AttributeLabel.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\AttributeLabel[]
     */
    public function all()
    {
        return AttributeLabel::all();
    }

    /**
     * Find an instance of AttributeLabel with the given ID.
     *
     * @param  int $id
     * @return \Pimeo\Models\AttributeLabel
     */
    public function find($id)
    {
        return AttributeLabel::find($id);
    }

    /**
     * Create a new instance of AttributeLabel.
     *
     * @param  array $attributes
     * @return \Pimeo\Models\AttributeLabel
     */
    public function create(array $attributes = [])
    {
        $count = AttributeLabel::where('name', 'LIKE', $attributes['name'] . '%')->count();

        $attributes['name'] .= '-' . $count;

        return AttributeLabel::create($attributes);
    }

    /**
     * Update the AttributeLabel with the given attributes.
     *
     * @param  int   $id
     * @param  array $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return AttributeLabel::find($id)->update($attributes);
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
        return AttributeLabel::find($id)->delete();
    }
}

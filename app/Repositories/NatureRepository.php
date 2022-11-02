<?php

namespace Pimeo\Repositories;

use Pimeo\Models\Nature;

class NatureRepository
{
    /**
     * Get all instance of Nature.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Nature[]
     */
    public function all()
    {
        return Nature::all();
    }

    /**
     * Find an instance of Nature with the given ID.
     *
     * @param  int  $id
     * @return \Pimeo\Models\Nature
     */
    public function find($id)
    {
        return Nature::find($id);
    }

    /**
     * Create a new instance of Nature.
     *
     * @param  array  $attributes
     * @return \Pimeo\Models\Nature
     */
    public function create(array $attributes = [])
    {
        return Nature::create($attributes);
    }

    /**
     * Update the Nature with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Nature::find($id)->update($attributes);
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
        return Nature::find($id)->delete();
    }

    public function getTranslatedByID($nature_id)
    {
        static $natures = null;
        // Liste des natures provenant de la table d'attributs
        if ($natures === null) {
            $natures = Nature::all()->keyBy('id');
        }

        return trans("attribute.natures.{$natures[$nature_id]->code}");
    }
}

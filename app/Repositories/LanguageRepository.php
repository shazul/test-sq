<?php

namespace Pimeo\Repositories;

use Pimeo\Models\Language;

class LanguageRepository
{
    /**
     * Get all instance of Language.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Language[]
     */
    public function all()
    {
        return Language::all();
    }

    /**
     * Find an instance of Language with the given ID.
     *
     * @param  int  $id
     * @return \Pimeo\Models\Language
     */
    public function find($id)
    {
        return Language::find($id);
    }

    /**
     * Create a new instance of Language.
     *
     * @param  array  $attributes
     * @return \Pimeo\Models\Language
     */
    public function create(array $attributes = [])
    {
        return Language::create($attributes);
    }

    /**
     * Update the Language with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Language::find($id)->update($attributes);
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
        return Language::find($id)->delete();
    }
}

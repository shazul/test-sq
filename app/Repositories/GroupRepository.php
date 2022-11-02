<?php

namespace Pimeo\Repositories;

use Pimeo\Models\Group;

class GroupRepository
{
    /**
     * Get all instance of Group.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Group[]
     */
    public function all()
    {
        return Group::all();
    }

    /**
     * Find an instance of Group with the given ID.
     *
     * @param  int  $id
     * @return \Pimeo\Models\Group
     */
    public function find($id)
    {
        return Group::find($id);
    }

    /**
     * Create a new instance of Group.
     *
     * @param  array  $attributes
     * @return \Pimeo\Models\Group
     */
    public function create(array $attributes = [])
    {
        return Group::create($attributes);
    }

    /**
     * Update the Group with the given attributes.
     *
     * @param  int    $id
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Group::find($id)->update($attributes);
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
        return Group::find($id)->delete();
    }

    /**
     * Get all instance except the ones with the given codes.
     *
     * @param array $codes
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Group[]
     */
    public function allExceptCodes($codes = [])
    {
        return Group::whereNotIn('code', $codes)->get();
    }

    /**
     * Get groups that can be managed by the given user.
     *
     * @param \Pimeo\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Group[]
     */
    public function manageableByUser($user)
    {
        if ($user->isSuperAdmin()) {
            return $this->allExceptCodes([Group::SUPER_ADMIN_CODE]);
        } elseif ($user->isAdmin()) {
            return $this->allExceptCodes([Group::SUPER_ADMIN_CODE, Group::ADMIN_CODE]);
        }

        return [];
    }
}

<?php

namespace Pimeo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Pimeo\Models\Group;
use Pimeo\Models\User;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Roles that can perform editor level actions.
     *
     * @var array
     */
    protected $minEditor = [Group::EDITOR_CODE, Group::ADMIN_CODE, Group::SUPER_ADMIN_CODE];

    /**
     * Roles that can perform admin level actions.
     *
     * @var array
     */
    protected $minAdmin = [Group::ADMIN_CODE, Group::SUPER_ADMIN_CODE];

    /**
     * Roles that can perform super admin level actions.
     *
     * @var array
     */
    protected $minSuper = [Group::SUPER_ADMIN_CODE];

    /**
     * Determine if the user can perform the create action.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole($this->minEditor);
    }

    /**
     * Determine if the user can perform the edit action.
     *
     * @param  User $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->hasRole($this->minEditor);
    }

    /**
     * Determine if the user can perform the delete action.
     *
     * @param  User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasRole($this->minEditor);
    }
}

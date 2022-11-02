<?php

namespace Pimeo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Pimeo\Models\Group;
use Pimeo\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    protected $roles = [Group::ADMIN_CODE, Group::SUPER_ADMIN_CODE];

    /**
     * Determine if the given user can manage others.
     *
     * @param  User $user
     * @return bool
     */
    public function manage(User $user)
    {
        return $user->hasRole($this->roles);
    }

    /**
     * Determine if the given user can edit another specific user.
     *
     * @param  User $user
     * @param  User $target
     * @return bool
     */
    public function edit(User $user, User $target)
    {
        if ((($user->isAdmin() && !$target->isSuperAdmin()) || $user->isSuperAdmin()) && $user->id != $target->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the given user can delete another specific user.
     *
     * @param  User $me
     * @param  User $user
     * @return bool
     */
    public function delete(User $me, User $user)
    {
        if ((($me->isAdmin() && !$user->isAdmin()) ||
                $me->isSuperAdmin()) && !$user->isSuperAdmin() && ($me->id != $user->id)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can perform the create action.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole($this->roles);
    }
}

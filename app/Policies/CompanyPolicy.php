<?php

namespace Pimeo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Pimeo\Models\Company;
use Pimeo\Models\Group;
use Pimeo\Models\User;

class CompanyPolicy
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
        return $user->isSuperAdmin();
    }

    /**
     * Determine if the given user can edit another specific user.
     *
     * @param  User $user
     * @param Company $company
     *
     * @return bool
     */
    public function edit(User $user, Company $company)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine if the given user can delete another specific user.
     *
     * @param  User $user
     * @param Company $company
     *
     * @return bool
     */
    public function delete(User $user, Company $company)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine if the user can perform the create action.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin();
    }
}

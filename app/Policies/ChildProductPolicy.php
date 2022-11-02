<?php

namespace Pimeo\Policies;

use Pimeo\Models\User;

class ChildProductPolicy extends BasePolicy
{
    /**
     * Determine if the user can approve a child product.
     *
     * @param  User $user
     * @return bool
     */
    public function approve(User $user)
    {
        return $user->hasRole($this->minEditor);
    }

    /**
     * Determine if the user can import child products.
     *
     * @param  User $user
     * @return bool
     */
    public function import(User $user)
    {
        return $user->hasRole($this->minAdmin);
    }
}

<?php

namespace Pimeo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Pimeo\Models\Attribute;
use Pimeo\Models\Group;
use Pimeo\Models\User;

class AttributePolicy
{
    use HandlesAuthorization;

    /**
     * Roles that can perform editor level actions.
     *
     * @var array
     */
    protected $minEditor = [Group::EDITOR_CODE, Group::ADMIN_CODE, Group::SUPER_ADMIN_CODE];

    /**
     * Determine if the user can manage the attributes.
     *
     * @param  User $user
     * @return bool
     */
    public function manage(User $user)
    {
        return $user->hasRole($this->minEditor);
    }

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
    public function edit(User $user, Attribute $attribute)
    {
        return $user->hasRole($this->minEditor) && !isset($attribute->options['not_editable']);
    }

    /**
     * Determine if the given attribute can be deleted by the user.
     *
     * @param  User      $user
     * @param  Attribute $attribute
     * @return bool
     */
    public function delete(User $user, Attribute $attribute)
    {
        return $attribute->type->public && !$attribute->is_min_requirement && $user->hasRole($this->minEditor);
    }
}

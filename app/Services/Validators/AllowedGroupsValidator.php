<?php

namespace Pimeo\Services\Validators;

use Pimeo\Models\Group;
use Pimeo\Repositories\GroupRepository;
use Pimeo\Services\Validators\Contracts\ValidatorContract;

class AllowedGroupsValidator implements ValidatorContract
{
    /**
     * @var GroupRepository
     */
    protected $groups;

    /**
     * @param GroupRepository $groups
     */
    public function __construct(GroupRepository $groups)
    {
        $this->groups = $groups;
    }

    /**
     * Validate allowed groups.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param array  $parameters
     * @return boolean
     */
    public function validate($attribute, $value, $parameters)
    {
        $forbiddenGroups = [
            Group::SUPER_ADMIN_CODE,
        ];

        if (auth()->user()->isAdmin()) {
            $forbiddenGroups[] = Group::ADMIN_CODE;
        }

        $allowedGroups = $this->groups->allExceptCodes($forbiddenGroups)->pluck('id')->toArray();

        return ! array_diff($value, $allowedGroups);
    }
}

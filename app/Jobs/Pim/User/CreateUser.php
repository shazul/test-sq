<?php

namespace Pimeo\Jobs\Pim\User;

use Illuminate\Support\Facades\Auth;
use Pimeo\Events\Pim\UserWasCreated;
use Pimeo\Jobs\Job;
use Pimeo\Repositories\UserRepository;

class CreateUser extends Job
{
    /**
     * The fields of the new user.
     *
     * @var array
     */
    protected $fields;

    /**
     * Create a new job instance.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     *
     * @param UserRepository $repository
     * @return void
     */
    public function handle(UserRepository $repository)
    {
        $user = $repository->create($this->processFields());

        $user->groups()->sync($this->getGroupsIds());
        $user->companies()->sync($this->getCompaniesIds());

        event(new UserWasCreated($user->fresh()));
    }

    /**
     * Process fields before storing them.
     *
     * @return array
     */
    protected function processFields()
    {
        $this->fields['password'] = bcrypt($this->fields['password']);
        $this->fields['active']   = isset($this->fields['active']) ? true : false;

        return $this->fields;
    }

    /**
     * Get the groups ids.
     *
     * @return array
     */
    protected function getGroupsIds()
    {
        return $this->fields['groups'];
    }

    protected function getCompaniesIds()
    {
        return $this->fields['companies'];
    }
}

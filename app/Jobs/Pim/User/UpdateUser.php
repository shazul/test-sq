<?php

namespace Pimeo\Jobs\Pim\User;

use Pimeo\Events\Pim\UserWasUpdated;
use Pimeo\Jobs\Job;
use Pimeo\Models\User;

class UpdateUser extends Job
{
    /**
     * The instance of the user to update.
     *
     * @var User
     */
    protected $user;

    /**
     * The fields of the user.
     *
     * @var array
     */
    protected $fields;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param array $fields
     */
    public function __construct(User $user, array $fields)
    {
        $this->user = $user;
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->update($this->processFields());
        $this->user->groups()->sync($this->getGroupsIds());

        $companyIds = $this->getCompaniesIds();
        if (!is_null($companyIds)) {
            $this->user->companies()->sync($companyIds);
        }

        event(new UserWasUpdated($this->user->fresh()));
    }

    /**
     * Process fields before storing them.
     *
     * @return array
     */
    protected function processFields()
    {
        if (isset($this->fields['password'])) {
            $this->fields['password'] = bcrypt($this->fields['password']);
        }
        $this->fields['active'] = isset($this->fields['active']) ? true : false;

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
        $ids = null;

        if (isset($this->fields['companies'])) {
            $ids = $this->fields['companies'];
        }

        return $ids;
    }
}

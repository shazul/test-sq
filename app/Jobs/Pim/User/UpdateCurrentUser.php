<?php

namespace Pimeo\Jobs\Pim\User;

use Pimeo\Events\Pim\UserWasUpdated;
use Pimeo\Jobs\Job;
use Pimeo\Models\User;

class UpdateCurrentUser extends Job
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
     * @param User  $user
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

        event(new UserWasUpdated($this->user->fresh()));
    }

    /**
     * Process fields before storing them.
     *
     * @return array
     */
    protected function processFields()
    {
        if (empty($this->fields['password'])) {
            unset($this->fields['password']);
        } else {
            $this->fields['password'] = bcrypt($this->fields['password']);
        }

        return $this->fields;
    }
}

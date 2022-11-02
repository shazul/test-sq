<?php

namespace Pimeo\Jobs\Pim\User;

use Pimeo\Events\Pim\UserWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\User;

class DeleteUser extends Job
{
    /**
     * The user to delete.
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->delete();

        event(new UserWasDeleted($this->user));
    }
}

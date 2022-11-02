<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Models\User;

class UserWasDeleted extends Event
{
    use SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user;
    }
}

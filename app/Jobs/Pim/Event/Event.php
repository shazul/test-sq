<?php

namespace Pimeo\Jobs\Pim\Event;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pimeo\Jobs\Job;

class Event extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $data;

    protected $eventName;

    public function __construct($eventName, $data)
    {
        $this->eventName = $eventName;
        $this->data = $data;
    }

    public function handle()
    {
        event(new $this->eventName($this->data));
    }
}

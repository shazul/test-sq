<?php

namespace Pimeo\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Pimeo\Events\Pim\AttributeWasUpdated'    => [
            'Pimeo\Listeners\Pim\TriggerModelsWasUpdatedEvent',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Pimeo\Listeners\Pim\DocumentIndexSubscriber',
        'Pimeo\Listeners\Pim\IndexDocument'
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}

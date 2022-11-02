<?php

namespace Pimeo\Listeners\Pim;

use Pimeo\Events\Pim\IndexDocumentEvent;
use Pimeo\Indexer\Indexer;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\ParentProduct;

class IndexDocument
{
    /** @var Indexer $indexer */
    protected $indexer;

    /** @var Company $company */
    protected $company;

    public function __construct()
    {
    }

    protected function setup(IndexDocumentEvent $event)
    {
        $indexerClass  = $event->indexer;
        $this->indexer = new $indexerClass($event->company);
    }

    public function indexDocument(IndexDocumentEvent $event)
    {
        $this->setup($event);

        if ($event->model->isIndexable()) {
            $this->indexer->indexOne($event->model->id);
        }
    }

    public function updateDocument(IndexDocumentEvent $event)
    {
        $this->setup($event);

        $this->indexer->deleteOne($event->model->id);

        if ($event->model->isIndexable()) {
            $this->indexer->indexOne($event->model->id);
        }
    }

    public function deleteDocument(IndexDocumentEvent $event)
    {
        $this->setup($event);

        $this->indexer->deleteOne($event->id);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Pimeo\Events\Pim\ChildProductWasUpdated',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\DetailWasUpdated',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\ParentProductWasUpdated',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\ChildProductWasDeleted',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SpecificationWasUpdated',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\TechnicalBulletinWasUpdated',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SystemLayerGroupWasCreated',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SystemLayerGroupWasDeleted',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SystemLayerGroupWasUpdated',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SystemWasUpdated',
            'Pimeo\Listeners\Pim\IndexDocument@updateDocument'
        );

        $events->listen(
            'Pimeo\Events\Pim\DetailWasCreated',
            'Pimeo\Listeners\Pim\IndexDocument@indexDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\ParentProductWasCreated',
            'Pimeo\Listeners\Pim\IndexDocument@indexDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SpecificationWasCreated',
            'Pimeo\Listeners\Pim\IndexDocument@indexDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\TechnicalBulletinWasCreated',
            'Pimeo\Listeners\Pim\IndexDocument@indexDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SystemWasCreated',
            'Pimeo\Listeners\Pim\IndexDocument@indexDocument'
        );

        $events->listen(
            'Pimeo\Events\Pim\DetailWasDeleted',
            'Pimeo\Listeners\Pim\IndexDocument@deleteDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\ParentProductWasDeleted',
            'Pimeo\Listeners\Pim\IndexDocument@deleteDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SpecificationWasDeleted',
            'Pimeo\Listeners\Pim\IndexDocument@deleteDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\TechnicalBulletinWasDeleted',
            'Pimeo\Listeners\Pim\IndexDocument@deleteDocument'
        );
        $events->listen(
            'Pimeo\Events\Pim\SystemWasDeleted',
            'Pimeo\Listeners\Pim\IndexDocument@deleteDocument'
        );
    }
}

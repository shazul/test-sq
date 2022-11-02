<?php

namespace Pimeo\Listeners\Pim;

use Pimeo\Events\Pim\AttributableModelIsIndexed;
use Pimeo\Events\Pim\AttributableModelIsRemovedFromIndex;
use Pimeo\Events\Pim\AttributableModelWasIndexed;
use Pimeo\Events\Pim\AttributableModelWasRemovedFromIndex;
use Pimeo\Indexer\ModelIndexers\DocumentIndexer;

class DocumentIndexSubscriber
{
    public function whenAttributableModelIsIndexed(AttributableModelIsIndexed $event)
    {
        $documentIndexer = new DocumentIndexer($event->indexer->getCompany());
        $documentIndexer->deleteAllFromAttributableModel(
            $event->attributableModel->id,
            get_class($event->attributableModel)
        );

        if ($event->attributableModel->isIndexable()) {
            $documentIndexer->indexAllFromAttributableModel($event->attributableModel);
        }
    }

    public function whenAttributableModelIsRemovedFromIndex(AttributableModelIsRemovedFromIndex $event)
    {
        $documentIndexer = new DocumentIndexer($event->indexer->getCompany());
        $documentIndexer->deleteAllFromAttributableModel($event->attributableModelId, $event->class);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Pimeo\Events\Pim\AttributableModelIsIndexed',
            'Pimeo\Listeners\Pim\DocumentIndexSubscriber@whenAttributableModelIsIndexed'
        );

        $events->listen(
            'Pimeo\Events\Pim\AttributableModelIsRemovedFromIndex',
            'Pimeo\Listeners\Pim\DocumentIndexSubscriber@whenAttributableModelIsRemovedFromIndex'
        );
    }
}

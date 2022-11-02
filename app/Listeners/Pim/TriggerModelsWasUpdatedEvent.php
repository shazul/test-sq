<?php

namespace Pimeo\Listeners\Pim;

use Pimeo\Events\Pim\AttributeWasUpdated;
use Pimeo\Indexer\ModelIndexers\AttributeScalableIndexer;
use Pimeo\Jobs\Pim\Event\Event;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeValue;
use Pimeo\Models\LinkAttribute;

class TriggerModelsWasUpdatedEvent
{
    public function __construct()
    {
    }

    public function handle(AttributeWasUpdated $event)
    {
        if ($event->oldValues == null) {
            return;
        }
        $diffKeys = $this->getDiffWithOldValues($event->attribute, $event->oldValues);

        if (in_array($event->attribute->type->code, ['choice_image_no_display', 'choice_multiple_image_no_display'])
            || $event->attribute->name == 'building_component'
        ) {
            /** @var Company $company */
            $company           = $event->attribute->company;
            $attribute_indexer = new AttributeScalableIndexer($company);
            $attribute_indexer->indexAll();
        }

        // Lance les events WasUpdated de tous les models qui utilisent cet attribut
        $linkedLinkAttributes = LinkAttribute::whereAttributeId($event->attribute->id)->get()->reject(function (
            LinkAttribute $linkAttribute
        ) use ($diffKeys) {
            if ($linkAttribute->attributable == null) {
                return true;
            }

            $linkAttributeValues = $linkAttribute->values;
            if (! empty($diffKeys)
                && isset($linkAttributeValues['keys'])
                && ! empty(array_intersect((array)$linkAttributeValues['keys'], (array)$diffKeys))
            ) {
                return false;
            }

            return true;
        });

        /** @var LinkAttribute $linkedLinkAttribute */
        foreach ($linkedLinkAttributes as $linkedLinkAttribute) {
            $modelName = class_basename($linkedLinkAttribute->attributable);
            $eventName = "Pimeo\\Events\\Pim\\" . $modelName . 'WasUpdated';
            event(new $eventName($linkedLinkAttribute->attributable));
        }
    }

    /**
     * @param Attribute $attribute
     * @param AttributeValue $oldValue
     *
     * @return mixed[]
     */
    private function getDiffWithOldValues(Attribute $attribute, AttributeValue $oldValue)
    {
        if (! $attribute->value) {
            return [];
        }

        $currentAttributeValues = $attribute->value->values;
        $oldValues              = $oldValue->values;

        $diffIds = [];
        foreach ($currentAttributeValues as $languageCode => $values) {
            $hasDif = $this->getArrayDiff($values, $oldValues[$languageCode]);
            if (! empty($hasDif)) {
                foreach ($hasDif as $key => $value) {
                    if (array_search($key, $diffIds) === false) {
                        $diffIds[] = $key;
                    }
                }
            }
        }

        return $diffIds;
    }

    /**
     * @param Mixed[] $array
     *
     * @return bool
     */
    private function isMultidimensional(array $array)
    {
        return (count($array) != count($array, 1));
    }

    /**
     * @param mixed[] $values
     * @param mixed[] $oldValues
     *
     * @return mixed[]
     */
    private function getArrayDiff($values, $oldValues)
    {
        if ($this->isMultidimensional($values)) {
            $hasDif = [];
            foreach ($values as $key => $value) {
                if (array_diff($value, $oldValues[$key])) {
                    $hasDif[$key] = $value;
                }
            }
        } else {
            $hasDif = array_diff($values, $oldValues);
        }

        return $hasDif;
    }
}

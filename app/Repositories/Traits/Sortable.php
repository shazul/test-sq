<?php

namespace Pimeo\Repositories\Traits;

trait Sortable
{
    /**
     * @param  string $sorting
     * @param  array $items
     * @param  string $langCode
     *
     * @return array
     */
    protected function sortResults($sorting, $items, $langCode)
    {
        list($column, $order) = explode(':', $sorting);

        $coll = collator_create('fr');

        /** @var Collection $items */
        $items = collect($items);
        $items = $items->sort(function ($a, $b) use ($column, $langCode, $coll) {
            $valueA = isset(
                $a[$column],
                $a[$column][$langCode]
            ) ? $a[$column][$langCode] : (isset($a[$column]) ? $a[$column] : '');
            $valueB = isset(
                $b[$column],
                $b[$column][$langCode]
            ) ? $b[$column][$langCode] : (isset($b[$column]) ? $b[$column] : '');

            if (is_array($valueA)) {
                $valueA = implode($valueA, '');
            }

            if (is_array($valueB)) {
                $valueB = implode($valueB, '');
            }

            return collator_compare($coll, $valueA, $valueB);
        });

        if ($order == 'desc') {
            $items = $items->reverse();
        }

        return $items->toArray();
    }
}

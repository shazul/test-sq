<?php

namespace Pimeo\Services\Fractal;

use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\SerializerAbstract;

class SopremaSerializer extends SerializerAbstract
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return ['data' => $data];
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return ['data' => $data];
    }

    /**
     * Serialize the included data.
     *
     * @param ResourceInterface $resource
     * @param array             $data
     *
     * @return array
     */
    public function includedData(ResourceInterface $resource, array $data)
    {
        return $data;
    }

    /**
     * Serialize the meta.
     *
     * @param array $meta
     *
     * @return array
     */
    public function meta(array $meta)
    {
        if (empty($meta)) {
            return [];
        }

        return ['meta' => $meta];
    }

    /**
     * Serialize the paginator.
     *
     * @param PaginatorInterface $paginator
     *
     * @return array
     */
    public function paginator(PaginatorInterface $paginator)
    {
        $current = (int) $paginator->getCurrentPage();
        $last = (int) $paginator->getLastPage();

        $links = [];

        if ($current > 1) {
            $links['first'] = $paginator->getUrl(1);
        }

        if ($current > 2) {
            $links['prev'] = $paginator->getUrl($current-1);
        }

        if ($current < $last - 1) {
            $links['next'] = $paginator->getUrl($current+1);
        }

        if ($current < $last) {
            $links['last'] = $paginator->getUrl($last);
        }

        return ['pagination' => $links];
    }

    /**
     * Serialize the cursor.
     *
     * @param CursorInterface $cursor
     *
     * @return array
     */
    public function cursor(CursorInterface $cursor)
    {
        return [];
    }
}

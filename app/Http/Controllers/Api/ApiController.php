<?php

namespace Pimeo\Http\Controllers\Api;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Models\Model;
use Pimeo\Transformers\BasicTransformer;
use Spatie\Fractal\Fractal;

abstract class ApiController extends Controller
{
    use AppNamespaceDetectorTrait;

    /**
     * @param  AbstractPaginator|Collection|Model $collection
     * @param  array                              $links
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($collection, array $links = [])
    {
        $fractal = $this->createFractal($collection);

        $this->setData($collection, $fractal);
        $this->applyMetas($links, $fractal);

        $data = $fractal->toArray();

        $headers = $this->createHeaders(array_get($data, 'meta', []));

        return response()->json($data, 200, $headers);
    }

    /**
     * Create headers with the given metas.
     *
     * @param  array $metas
     * @return array
     */
    protected function createHeaders(array $metas)
    {
        $headers = [];

        $metaLinks = array_flatten($metas, 1);

        if (count($metaLinks)) {
            $links = [];
            foreach ($metaLinks as $relation => $url) {
                $links[] = '<' . $url . '>; rel="' . $relation . '"';
            }

            if (!empty($links)) {
                $headers['Links'] = $links;
            }
        }

        return $headers;
    }

    /**
     * Get the transformer class for the given object.
     *
     * @param  mixed $resource
     * @return string
     */
    protected function getTransformer($resource)
    {
        $class = new \ReflectionClass($resource);

        $transformer = $this->getAppNamespace() . 'Transformers\\' . ucfirst($class->getShortName()) . 'Transformer';

        return $transformer;
    }

    /**
     * Set the data of the fractal and the data transformer.
     *
     * @param Collection|Model $collection
     * @param Fractal          $fractal
     */
    protected function setData($collection, Fractal $fractal)
    {
        if ($collection instanceof Collection) {
            $item = $collection->first();

            if ($item) {
                $transformer = $this->getTransformer($collection->first());
            } else {
                $transformer = BasicTransformer::class;
            }

            $fractal->collection($collection);
        } else {
            if ($collection) {
                $transformer = $this->getTransformer($collection);
            } else {
                $transformer = BasicTransformer::class;
            }

            $fractal->item($collection);
        }

        $fractal->transformWith(new $transformer);
    }

    /**
     * Create an instance of fractal and change the collection variable if it's a paginator.
     *
     * @param  Collection|AbstractPaginator|Model $collection
     * @return Fractal
     */
    protected function createFractal(&$collection)
    {
        $fractal = fractal();

        if ($collection instanceof LengthAwarePaginator) {
            $paginator = $collection;
            $collection = $paginator->getCollection();

            $fractal->paginateWith(new IlluminatePaginatorAdapter($paginator));
        }

        return $fractal;
    }

    /**
     * Apply the metas to the fractal object.
     *
     * @param array   $links
     * @param Fractal $fractal
     */
    protected function applyMetas(array $links, Fractal $fractal)
    {
        if (count($links)) {
            $fractal->addMeta(['links' => $links]);
        }
    }
}

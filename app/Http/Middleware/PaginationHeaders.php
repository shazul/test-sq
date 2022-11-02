<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class PaginationHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        $metaLinks = array_merge(
            array_get($response->original, 'meta.pagination', []),
            array_get($response->original, 'meta.links', [])
        );

        if ($metaLinks) {
            $links = $response->headers->get('Links', []);

            $response->header('Links', array_merge($links, $this->handlePagination($metaLinks)));
        }

        return $response;
    }

    protected function handlePagination(array $pagination)
    {
        $links = [];

        foreach ($pagination as $relation => $url) {
            $links[] = '<' . $url . '>; rel="' . $relation . '"';
        }

        return $links;
    }
}

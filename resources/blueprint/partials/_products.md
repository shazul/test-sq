# Group Products

## Products [/products]


### Get products [GET /products{?page,per_page,lang}]
Get all products

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    <!-- include(requests/parameters/_pagination.md) -->
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.products') }}?page=1>; rel="first", <{{ route('api.products') }}?page=3>; rel="prev", <{{ route('api.products') }}?page=5>; rel="next", <{{ route('api.products') }}?page=501>; rel="last"
        <!-- include(responses/headers/_rate.md) -->

    + Body
{
    "data": [
        {
            "id": 7,
            "company_id": 1,
            "product_name": [
                {
                    "fr": "qui incidunt consequuntur",
                    "en": "dolores id nihil"
                }
            ]
        },
        {
            "id": 8,
            "company_id": 1,
            "product_name": [
                {
                    "fr": "nihil et et",
                    "en": "nostrum totam ut"
                }
            ]
        }
    ],
    "meta": {
        "pagination": {
            "first": "{{ route('api.products') }}?page=1",
            "prev": "{{ route('api.products') }}?page=3",
            "next": "{{ route('api.products') }}?page=5",
            "last": "{{ route('api.products') }}?page=500"
        }
    }
}

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->


### Get product [GET /products/{product}{?lang}]
Get a product

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    + product: `7` (integer) - Id of the product
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.products') }}>; rel="products", <{{ route('api.products') }}/1?with_children=true>; rel="children"
        <!-- include(responses/headers/_rate.md) -->
    
    + Body
        {
            "data": {
                "id": 7,
                "company_id": 1,
                "product_name": [
                    {
                        "fr": "qui incidunt consequuntur",
                        "en": "dolores id nihil"
                    }
                ]
            },
             "meta": {
                 "links": {
                     "products": "{{ route('api.products') }}"
                 }
             }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->


### Get product's children [GET /products/{product}{?with_children,lang}]
Get all children of a product

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    + product: `7` (integer) - If of the product
    + with_children: `true` (boolean, optional) - Include product's children
        + default: false
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.products') }}/1>; rel="product"
        <!-- include(responses/headers/_rate.md) -->
    
    + Body
        {
            "data": {
                "id": 7,
                "company_id": 1,
                "product_name": [
                    {
                        "fr": "qui incidunt consequuntur",
                        "en": "dolores id nihil"
                    }
                ],
                "children": [
                    {
                        "id": 14,
                        "company_id": 1,
                        "parent_product_id": 7,
                        "product_name": [
                            {
                                "fr": "omnis est assumenda",
                                "en": "modi cum vero"
                            }
                        ]
                    },
                    {
                        "id": 13,
                        "company_id": 1,
                        "parent_product_id": 7,
                        "product_name": [
                            {
                                "fr": "est quibusdam explicabo",
                                "en": "quasi nihil rerum"
                            }
                        ]
                    }
                ]
            },
            "meta": {
                "links": {
                    "products": "{{ route('api.products') }}"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->

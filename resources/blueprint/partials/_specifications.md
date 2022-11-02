# Group Specifications

## Specifications [/specifications]


### Get specifications [GET /specifications{?page,per_page,lang}]
Get all specifications

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    <!-- include(requests/parameters/_pagination.md) -->
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.specifications') }}?page=1>; rel="first", <{{ route('api.specifications') }}?page=3>; rel="prev", <{{ route('api.specifications') }}?page=5>; rel="next", <{{ route('api.specifications') }}?page=500>; rel="last"
        <!-- include(responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "id": 7,
                    "company_id": 1,
                    "specification_name": [
                        {
                            "fr": "aut et culpa",
                            "en": "omnis doloremque est"
                        }
                    ]
                },
                {
                    "id": 8,
                    "company_id": 1,
                    "specification_name": [
                        {
                            "fr": "deserunt quaerat officiis",
                            "en": "soluta minus quod"
                        }
                    ]
                }
            ],
            "meta": {
                "pagination": {
                    "first": "{{ route('api.specifications') }}?page=1",
                    "prev": "{{ route('api.specifications') }}?page=3",
                    "next": "{{ route('api.specifications') }}?page=5",
                    "last": "{{ route('api.specifications') }}?page=500"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->


### Get a specification [GET /specifications/{specification}{?lang}]
Get a specification object

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    + specification: `7` (integer) - Id of the specification to fetch
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.specifications') }}>; rel="specifications"
        <!-- include(responses/headers/_rate.md) -->
    
    + Body
        {
            "data": {
                "id": 7,
                "company_id": 1,
                "specification_name": [
                    {
                        "fr": "aut et culpa",
                        "en": "omnis doloremque est"
                    }
                ]
            },
            "meta": {
                "links": {
                    "specifications": "{{ route('api.specifications') }}"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->

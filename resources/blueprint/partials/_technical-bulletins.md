# Group Technical Bulletins

## Technical Bulletins [/technical-bulletins]


### Get technical-bulletins [GET /technical-bulletins{?page,per_page,lang}]
Get all technical bulletins

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    <!-- include(requests/parameters/_pagination.md) -->
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.technical-bulletins') }}?page=1>; rel="first", <{{ route('api.technical-bulletins') }}?page=3>; rel="prev", <{{ route('api.technical-bulletins') }}?page=5>; rel="next", <{{ route('api.technical-bulletins') }}?page=500>; rel="last"
        <!-- include(responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "id": 7,
                    "company_id": 1,
                    "technical_bulletin_name": [
                        {
                            "fr": "aut et culpa",
                            "en": "omnis doloremque est"
                        }
                    ]
                },
                {
                    "id": 8,
                    "company_id": 1,
                    "technical_bulletin_name": [
                        {
                            "fr": "deserunt quaerat officiis",
                            "en": "soluta minus quod"
                        }
                    ]
                }
            ],
            "meta": {
                "pagination": {
                    "first": "{{ route('api.technical-bulletins') }}?page=1",
                    "prev": "{{ route('api.technical-bulletins') }}?page=3",
                    "next": "{{ route('api.technical-bulletins') }}?page=5",
                    "last": "{{ route('api.technical-bulletins') }}?page=500"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->


### Get a technical bulletin [GET /technical-bulletins/{technical_bulletin}{?lang}]
Get a technical bulletin object

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    + technical_bulletin: `7` (integer) - Id of the technical bulletin to fetch
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.technical-bulletins') }}>; rel="technical-bulletins"
        <!-- include(responses/headers/_rate.md) -->
    
    + Body
        {
            "data": {
                "id": 7,
                "company_id": 1,
                "technical_bulletin_name": [
                    {
                        "fr": "aut et culpa",
                        "en": "omnis doloremque est"
                    }
                ]
            },
            "meta": {
                "links": {
                    "technical-bulletins": "{{ route('api.technical-bulletins') }}"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->

# Group Systems

## Systems [/details]


### Get systems [GET /systems{?page,per_page,lang}]
Get all systems

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    <!-- include(requests/parameters/_pagination.md) -->
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.systems') }}?page=1>; rel="first", <{{ route('api.systems') }}?page=3>; rel="prev", <{{ route('api.systems') }}?page=5>; rel="next", <{{ route('api.systems') }}?page=500>; rel="last"
        <!-- include(responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "id": 7,
                    "company_id": 1,
                    "system_name": [
                        {
                            "fr": "tempore deleniti corrupti",
                            "en": "repellat non perspiciatis"
                        }
                    ]
                },
                {
                    "id": 8,
                    "company_id": 1,
                    "system_name": [
                        {
                            "fr": "facere est autem",
                            "en": "quis error deserunt"
                        }
                    ]
                }
            ],
            "meta": {
                "pagination": {
                    "first": "{{ route('api.systems') }}?page=1",
                    "prev": "{{ route('api.systems') }}?page=3",
                    "next": "{{ route('api.systems') }}?page=5",
                    "last": "{{ route('api.systems') }}?page=500"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->


### Get system [GET /systems/{system}{?lang}]
Get a system

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    + system: `7` (integer) - Id of the system
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.systems') }}>; rel="systems"
        <!-- include(responses/headers/_rate.md) -->

    + Body
        {
            "data": {
                "id": 7,
                "company_id": 1,
                "system_name": [
                    {
                        "fr": "tempore deleniti corrupti",
                        "en": "repellat non perspiciatis"
                    }
                ]
            },
            "meta": {
                "links": {
                    "systems": "{{ route('api.systems') }}"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->

# Group Details

## Details [/details]


### Get details [GET /details{?page,per_page,lang}]
Get all details

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    <!-- include(requests/parameters/_pagination.md) -->
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.details') }}?page=1>; rel="first", <{{ route('api.details') }}?page=3>; rel="prev", <{{ route('api.details') }}?page=5>; rel="next", <{{ route('api.details') }}?page=501>; rel="last"
        <!-- include(responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "id": 7,
                    "company_id": 1,
                    "detail_name": [
                        {
                            "fr": "voluptate libero vel",
                            "en": "tenetur alias nam"
                        }
                    ]
                },
                {
                    "id": 8,
                    "company_id": 1,
                    "detail_name": [
                        {
                            "fr": "officiis rerum voluptatem",
                            "en": "perferendis nobis in"
                        }
                    ]
                }
            ],
            "meta": {
                "pagination": {
                    "first": "{{ route('api.details') }}?page=1",
                    "prev": "{{ route('api.details') }}?page=3",
                    "next": "{{ route('api.details') }}?page=5",
                    "last": "{{ route('api.details') }}?page=501"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->


### Get detail [GET /details/{detail}{?lang}]
Get a detail

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    + detail: `7` (integer) - Id of the detail
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.details') }}>; rel="details"
        <!-- include(responses/headers/_rate.md) -->

    + Body
        {
            "data": {
                "id": 7,
                "company_id": 1,
                "detail_name": [
                    {
                        "fr": "voluptate libero vel",
                        "en": "tenetur alias nam"
                    }
                ]
            },
            "meta": {
                "links": {
                    "details": "{{ route('api.details') }}"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->

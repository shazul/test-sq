### Get company's products [GET /companies/{company}/products{?page,per_page,lang}]
Get all company's products

+ Request
    + Headers
        <!-- include(../requests/_headers.md) -->

+ Parameters
    + company: `1` (integer) - Id of the company
    <!-- include(../requests/parameters/_pagination.md) -->
    <!-- include(../requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.company.products', 1) }}>; rel="company", <{{ route('api.company.products', 1) }}?page=1>; rel="first", <{{ route('api.company.products', 1) }}?page=3>; rel="prev", <{{ route('api.company.products', 1) }}?page=5>; rel="next", <{{ route('api.company.products', 1) }}?page=500>; rel="last"
        <!-- include(../responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "id": 7,
                    "company_id": 1,
                    "product_name": [
                        {
                            "fr": "tempore dolores veniam",
                            "en": "vel possimus nulla"
                        }
                    ]
                },
                {
                    "id": 8,
                    "company_id": 1,
                    "product_name": [
                        {
                            "fr": "laborum saepe earum",
                            "en": "distinctio sed atque"
                        }
                    ]
                }
            ],
            "meta": {
                "links": {
                    "company": "{{ route('api.company.products', 1) }}"
                },
                "pagination": {
                    "first": "{{ route('api.company.products', 1) }}?page=1",
                    "prev": "{{ route('api.company.products', 1) }}?page=3",
                    "next": "{{ route('api.company.products', 1) }}?page=5",
                    "last": "{{ route('api.company.products', 1) }}?page=500"
                }
            }
        }

<!-- include(../responses/_404.md) -->

<!-- include(../responses/_403.md) -->

<!-- include(../responses/_400.md) -->

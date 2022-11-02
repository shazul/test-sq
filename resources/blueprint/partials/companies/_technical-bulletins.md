### Get company's technical bulletins [GET /companies/{company}/technical-bulletins{?page,per_page,lang}]
Get all company's technical bulletins

+ Request
    + Headers
        <!-- include(../requests/_headers.md) -->

+ Parameters
    + company: `1` (integer) - Id of the company
    <!-- include(../requests/parameters/_pagination.md) -->
    <!-- include(../requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.companies', 1) }}>; rel="company", <{{ route('api.company.technical-bulletins', 1) }}?page=1>; rel="first", <{{ route('api.company.technical-bulletins', 1) }}?page=3>; rel="prev", <{{ route('api.company.technical-bulletins', 1) }}?page=5>; rel="next", <{{ route('api.company.technical-bulletins', 1) }}?page=500>; rel="last"
        <!-- include(../responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "id": 1008,
                    "company_id": 1,
                    "technical_bulletin_name": [
                        {
                            "fr": "aut et culpa",
                            "en": "omnis doloremque est"
                        }
                    ]
                },
                {
                    "id": 1009,
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
                "links": {
                    "company": "{{ route('api.companies', 1) }}"
                },
                "pagination": {
                    "first": "{{ route('api.company.technical-bulletins', 1) }}?page=1",
                    "prev": "{{ route('api.company.technical-bulletins', 1) }}?page=3",
                    "next": "{{ route('api.company.technical-bulletins', 1) }}?page=5",
                    "last": "{{ route('api.company.technical-bulletins', 1) }}?page=500"
                }
            }
        }

<!-- include(../responses/_404.md) -->

<!-- include(../responses/_403.md) -->

<!-- include(../responses/_400.md) -->

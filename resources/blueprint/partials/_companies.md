# Group Companies


## Companies [/companies]


### Get companies [GET /companies{?page,per_page,lang}]
Get a list of companies

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    <!-- include(requests/parameters/_pagination.md) -->
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.companies') }}?page=1>; rel="first", <{{ route('api.companies') }}?page=3>; rel="prev", <{{ route('api.companies') }}?page=5>; rel="next", <{{ route('api.companies') }}?page=501>; rel="last"
        <!-- include(responses/headers/_rate.md) -->
        
    + Body
        {
            "data": [
                {
                    "id": 7,
                    "name": "Torp-Denesik",
                    "language": {
                        "name": "English (Canada)",
                        "code": "en"
                    }
                },
                {
                    "id": 8,
                    "name": "Bogan-Pollich",
                    "language": {
                        "name": "Français (Canada)",
                        "code": "fr"
                    }
                }
            ],
            "meta": {
                "pagination": {
                    "first": "{{ route('api.companies') }}?page=1",
                    "prev": "{{ route('api.companies') }}?page=3",
                    "next": "{{ route('api.companies') }}?page=5",
                    "last": "{{ route('api.companies') }}?page=501"
                }
            }
        }

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->


### Get company [GET /companies/{company}{?lang}]
Get a company

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Parameters
    + company: `7` (integer) - Id of the company to fetch.
    <!-- include(requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.companies') }}>; rel="companies"
        <!-- include(responses/headers/_rate.md) -->
        
    + Body
        {
            "data": {
                "id": 7,
                "name": "Torp-Denesik",
                "language": {
                    "name": "English (Canada)",
                    "code": "en"
                }
            },
            "meta": {
                "links": {
                    "companies": "{{ route('api.companies') }}"
                }
            }
        }

<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->


<!-- include(companies/_products.md) -->


<!-- include(companies/_systems.md) -->


<!-- include(companies/_details.md) -->


<!-- include(companies/_specifications.md) -->


<!-- include(companies/_technical-bulletins.md) -->


<!-- include(companies/_languages.md) -->


<!-- include(companies/_medias.md) -->

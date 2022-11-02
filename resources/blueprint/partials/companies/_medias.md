### Get company's medias [GET /companies/{company}/medias{?page,per_page,lang}]
Get all company's medias

+ Request
    + Headers
        <!-- include(../requests/_headers.md) -->

+ Parameters
    + company: `1` (integer) - Id of the company
    <!-- include(../requests/parameters/_pagination.md) -->
    <!-- include(../requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <http://pim.soprema.local/api/companies/1>; rel="company"
        <!-- include(../responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "id": 1,
                    "code": "website",
                    "name": "website"
                }
            ],
            "meta": {
                "links": {
                    "company": "http://pim.soprema.local/api/companies/1"
                }
            }
        }

<!-- include(../responses/_404.md) -->

<!-- include(../responses/_403.md) -->

<!-- include(../responses/_400.md) -->


### Get media's products [GET /companies/{company}/medias/{media}/products{?page,per_page,lang}]
Get all products related to the media in a company

+ Request
    + Headers
        <!-- include(../requests/_headers.md) -->

+ Parameters
    + company: `1` (integer) - Id of the company
    + media: `1` (integer) - Id of the media
    <!-- include(../requests/parameters/_pagination.md) -->
    <!-- include(../requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <{{ route('api.company.medias', 1) }}>; rel="medias", <{{ route('api.company.media.products', \[1, 1\]) }}?page=1>; rel="first", <{{ route('api.company.media.products', \[1, 1\]) }}?page=3>; rel="prev", <{{ route('api.company.media.products', \[1, 1\]) }}?page=5>; rel="next", <{{ route('api.company.media.products', \[1, 1\]) }}?page=1000>; rel="last"
        <!-- include(../responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "id": 7,
                    "company_id": 1,
                    "parent_product_id": 4,
                    "company_catalog_product_id": 1,
                    "product_name": [
                        {
                            "fr": "voluptas repudiandae laborum",
                            "en": "animi dolorem explicabo"
                        }
                    ],
                    "medias": [
                        {
                            "id": 1,
                            "name": "website",
                            "code": "website"
                        }
                    ]
                },
                {
                    "id": 8,
                    "company_id": 1,
                    "parent_product_id": 4,
                    "company_catalog_product_id": 1,
                    "product_name": [
                        {
                            "fr": "est assumenda omnis",
                            "en": "laborum ratione dolorem"
                        }
                    ],
                    "medias": [
                        {
                            "id": 1,
                            "name": "website",
                            "code": "website"
                        }
                    ]
                }
            ],
            "meta": {
                "links": {
                    "medias": "{{ route('api.company.medias', 1) }}"
                },
                "pagination": {
                    "first": "{{ route('api.company.media.products', \[1, 1\]) }}?page=1",
                    "prev": "{{ route('api.company.media.products', \[1, 1\]) }}?page=3",
                    "next": "{{ route('api.company.media.products', \[1, 1\]) }}?page=5",
                    "last": "{{ route('api.company.media.products', \[1, 1\]) }}?page=1000"
                }
            }
        }

<!-- include(../responses/_404.md) -->

<!-- include(../responses/_403.md) -->

<!-- include(../responses/_400.md) -->

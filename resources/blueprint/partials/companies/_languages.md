### Get company's languages [GET /companies/{company}/languages{?lang}]
Get all company's languages

+ Request
    + Headers
        <!-- include(../requests/_headers.md) -->

+ Parameters
    + company: `1` (integer) - Id of the company
    <!-- include(../requests/parameters/_language.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        Link: <http://pim.soprema.local/api/companies/1>; rel="company"
        <!-- include(../responses/headers/_rate.md) -->
    
    + Body
        {
            "data": [
                {
                    "name": "Français (Canada)",
                    "code": "fr"
                },
                {
                    "name": "English (Canada)",
                    "code": "en"
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
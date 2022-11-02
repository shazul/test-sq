# Group Languages


## Languages [/languages]


### Get languages [GET /languages]
Get all languages

+ Request
    + Headers
        <!-- include(requests/_headers.md) -->

+ Response 200 (application/json; charset=utf-8)
    + Headers
        <!-- include(responses/headers/_rate.md) -->
    
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
            ]
        }
        
<!-- include(responses/_404.md) -->

<!-- include(responses/_403.md) -->

<!-- include(responses/_400.md) -->

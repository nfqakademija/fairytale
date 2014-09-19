Feature: API - User
    In order to manipulate user resource
    As an API user
    I need to be able perform certain actions

    Scenario: Unauthorized access is denied
        When I send a GET request to "/api/user/1"
        Then the response code should be 403

    Scenario: It should be possible to get user details
        Given I am authenticated as "admin"
        When I send a GET request to "/api/user/1"
        Then the response code should be 200
        And I should get JSON response with following format:
            | field             | type    |
            | id                | integer |
            | email             | string  |
            | name              | string  |
            | lastname          | string  |
            | comments          | array   |
            | ratings           | array   |
            | reservations      | array   |
            | image             | array   |
            | image.user_medium | string  |
            | image.user_small  | string  |

    Scenario: It should be possible to get all user's returned books
        Given I am authenticated as "user"
        When I send a GET request to "/api/user/2/returned"
        Then the response code should be 200
        And I should get JSON collection with following format:
            | field              | type    |
            | id                 | integer |
            | title              | string  |
            | description        | string  |
            | pages              | integer |
            | publisher          | string  |
            | isbn               | string  |
            | cover              | string  |
            | language           | string  |
            | categories         | array   |
            | categories.0.id    | integer |
            | categories.0.title | string  |
            | authors            | array   |
            | authors.0.id       | integer |
            | authors.0.name     | string  |
            | image              | array   |
            | image.book_big     | string  |
            | image.book_medium  | string  |
            | image.book_small   | string  |
            | image.book_tiny    | string  |

    Scenario: It should be possible to get all favorite categories
        Given I am authenticated as "user"
        When I send a GET request to "/api/user/2/categories"
        Then the response code should be 200
        And I should get JSON collection with following format:
            | field | type    |
            | id    | integer |
            | title | string  |

    Scenario: It should be possible to get all books user is currently reading
        Given I am authenticated as "user"
        When I send a GET request to "/api/user/2/reading"
        Then the response code should be 200
        And I should get JSON collection with following format:
            | field              | type    |
            | id                 | integer |
            | title              | string  |
            | description        | string  |
            | pages              | integer |
            | publisher          | string  |
            | isbn               | string  |
            | cover              | string  |
            | language           | string  |
            | categories         | array   |
            | categories.0.id    | integer |
            | categories.0.title | string  |
            | authors            | array   |
            | authors.0.id       | integer |
            | authors.0.name     | string  |
            | image              | array   |
            | image.book_big     | string  |
            | image.book_medium  | string  |
            | image.book_small   | string  |
            | image.book_tiny    | string  |

    Scenario: It should be possible to get all books user has currently reserved
        Given I am authenticated as "user"
        When I send a GET request to "/api/user/2/reservation"
        Then the response code should be 200
        And I should get JSON collection with following format:
            | field              | type    |
            | id                 | integer |
            | title              | string  |
            | description        | string  |
            | pages              | integer |
            | publisher          | string  |
            | isbn               | string  |
            | cover              | string  |
            | language           | string  |
            | categories         | array   |
            | categories.0.id    | integer |
            | categories.0.title | string  |
            | authors            | array   |
            | authors.0.id       | integer |
            | authors.0.name     | string  |
            | image              | array   |
            | image.book_big     | string  |
            | image.book_medium  | string  |
            | image.book_small   | string  |
            | image.book_tiny    | string  |

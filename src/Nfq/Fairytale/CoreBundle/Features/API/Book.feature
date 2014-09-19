Feature: API - Book
    In order to manipulate book resource
    As an API user
    I need to be able perform certain actions

    Scenario: Unauthorized access is denied
        When I send a GET request to "/api/book/1"
        Then the response code should be 403

    Scenario: It should be possible to get book details
        Given I am authenticated as "user"
        When I send a GET request to "/api/book/1"
        Then the response code should be 200
        And I should get JSON response with following format:
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
            | status             | string  |

    Scenario: It should be possible to get most popular books
        Given I am authenticated as "user"
        When I send a GET request to "/api/book/1"
        Then the response code should be 200
        And I should get JSON response with following format:
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
            | status             | string  |

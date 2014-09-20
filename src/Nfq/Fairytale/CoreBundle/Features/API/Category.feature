Feature: API - Category
    In order to manipulate category resource
    As an API user
    I need to be able perform certain actions

    Scenario: Unauthorized access is denied
        When I send a GET request to "/api/category/1"
        Then the response code should be 403

    Scenario: It should be possible to get category details
        Given I am authenticated as "user"
        When I send a GET request to "/api/category/1"
        Then the response code should be 200
        And I should get JSON response with following format:
            | field                      | type    |
            | id                         | integer |
            | title                      | string  |
            | books                      | array   |
            | books.0.id                 | integer |
            | books.0.title              | string  |
            | books.0.description        | string  |
            | books.0.pages              | integer |
            | books.0.publisher          | string  |
            | books.0.isbn               | string  |
            | books.0.cover              | string  |
            | books.0.language           | string  |
            | books.0.categories         | array   |
            | books.0.categories.0.id    | integer |
            | books.0.categories.0.title | string  |
            | books.0.authors            | array   |
            | books.0.authors.0.id       | integer |
            | books.0.authors.0.name     | string  |
            | books.0.image              | array   |
            | books.0.image.book_big     | string  |
            | books.0.image.book_medium  | string  |
            | books.0.image.book_small   | string  |
            | books.0.image.book_tiny    | string  |
            | books.0.status             | string  |

    Scenario: It should be possible to get category details
        Given I am authenticated as "user"
        When I send a GET request to "/api/category/new"
        Then the response code should be 200
        And I should get JSON response with following format:
            | field                      | type    |
            | id                         | string  |
            | title                      | string  |
            | books                      | array   |
            | books.0.id                 | integer |
            | books.0.title              | string  |
            | books.0.description        | string  |
            | books.0.pages              | integer |
            | books.0.publisher          | string  |
            | books.0.isbn               | string  |
            | books.0.cover              | string  |
            | books.0.language           | string  |
            | books.0.categories         | array   |
            | books.0.categories.0.id    | integer |
            | books.0.categories.0.title | string  |
            | books.0.authors            | array   |
            | books.0.authors.0.id       | integer |
            | books.0.authors.0.name     | string  |
            | books.0.image              | array   |
            | books.0.image.book_big     | string  |
            | books.0.image.book_medium  | string  |
            | books.0.image.book_small   | string  |
            | books.0.image.book_tiny    | string  |
            | books.0.status             | string  |

    Scenario: It should be possible to get category details
        Given I am authenticated as "user"
        When I send a GET request to "/api/category/popular"
        Then the response code should be 200
        And I should get JSON response with following format:
            | field                      | type    |
            | id                         | string  |
            | title                      | string  |
            | books                      | array   |
            | books.0.id                 | integer |
            | books.0.title              | string  |
            | books.0.description        | string  |
            | books.0.pages              | integer |
            | books.0.publisher          | string  |
            | books.0.isbn               | string  |
            | books.0.cover              | string  |
            | books.0.language           | string  |
            | books.0.categories         | array   |
            | books.0.categories.0.id    | integer |
            | books.0.categories.0.title | string  |
            | books.0.authors            | array   |
            | books.0.authors.0.id       | integer |
            | books.0.authors.0.name     | string  |
            | books.0.image              | array   |
            | books.0.image.book_big     | string  |
            | books.0.image.book_medium  | string  |
            | books.0.image.book_small   | string  |
            | books.0.image.book_tiny    | string  |
            | books.0.status             | string  |

    Scenario: It should be possible to get category details
        Given I am authenticated as "user"
        When I send a GET request to "/api/category/all"
        Then the response code should be 200
        And I should get JSON response with following format:
            | field                      | type    |
            | id                         | string  |
            | title                      | string  |
            | books                      | array   |
            | books.0.id                 | integer |
            | books.0.title              | string  |
            | books.0.description        | string  |
            | books.0.pages              | integer |
            | books.0.publisher          | string  |
            | books.0.isbn               | string  |
            | books.0.cover              | string  |
            | books.0.language           | string  |
            | books.0.categories         | array   |
            | books.0.categories.0.id    | integer |
            | books.0.categories.0.title | string  |
            | books.0.authors            | array   |
            | books.0.authors.0.id       | integer |
            | books.0.authors.0.name     | string  |
            | books.0.image              | array   |
            | books.0.image.book_big     | string  |
            | books.0.image.book_medium  | string  |
            | books.0.image.book_small   | string  |
            | books.0.image.book_tiny    | string  |
            | books.0.status             | string  |

@api
Feature: CRUD
    In order to use API
    As an API user
    An API has to support basic CRUD operations

    Scenario: I can read user
        Given I am authenticated as "admin"
        When I send a GET request to "/api/user/1"
        Then print last response
        Then the response code should be 200
        And the response should be json:
        """
        {
            "id": 1,
            "name":"The Admin",
            "email":"email_bar@api.com",
            "password": "pass_secret"
        }
        """

    Scenario: I can read user index
        Given I am authenticated as "admin"
        When I send a GET request to "/api/user"
        Then print last response
        Then the response code should be 200
        And the response should be json:
        """
        [
            {
                "id": 1,
                "name":"name_Foo",
                "email":"email_bar@api.com",
                "password": "pass_secret"
            },
            {
                "id": 2,
                "name": "Baz",
                "author": "qux@api.com",
                "password": "dont tell anyone"
            }
        ]
        """

    Scenario: I can skip 5 test items and read 5 following
        Given I am authenticated as "admin"
        When I send a GET request to "/api/testItem?limit=5&offset=5"
        Then print last response
        Then the response code should be 200
        And the response should be json:
        """
        [
            {"id":6,"foo":"bar"},
            {"id":7,"foo":"bar"},
            {"id":8,"foo":"bar"},
            {"id":9,"foo":"bar"},
            {"id":10,"foo":"bar"}
        ]
        """

    Scenario: I can create user
        Given I am authenticated as "admin"
        When I send a POST request to "/api/user" with body:
        """
        {
            "name":"name_Bob",
            "email":"email_Bob@api.com",
            "password": "pass_Bob"
        }
        """
        Then print last response
        Then the response code should be 201
        And the response should be json:
        """
        {
            "id": 3,
            "name":"name_Bob",
            "email":"email_Bob@api.com",
            "password": "pass_Bob"
        }
        """

    Scenario: I can update user
        Given I am authenticated as "admin"
        When I send a PUT request to "/api/user/3" with body:
        """
        {
            "name":"John Doe"
        }
        """
        Then print last response
        Then the response code should be 200
        And the response should be json:
        """
        {
            "id": 3,
            "name":"John Doe",
            "email":"email_Bob@api.com",
            "password": "pass_Bob"
        }
        """

    Scenario: I can delete user
        Given I am authenticated as "admin"
        When I send a DELETE request to "/api/user/3"
        Then print last response
        Then the response code should be 200
        And the response should be json: 
        """
        {
            "status":"success"
        }
        """
        And I send a GET request to "/api/user/3"
        And the response code should be 404

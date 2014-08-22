@api
Feature: CRUD
    In order to use API
    As an API user
    An API has to support basic CRUD operations

    Scenario: I can read user
        Given I have "admin" access token
        When I send a GET request to "/api/user/1"
        Then the response code should be 200
        And the response should contain json:
        """
        {
            "id": 1,
            "name":"name_Foo",
            "email":"email_bar@api.com",
            "password": "pass_secret"
        }
        """

    Scenario: I can read user index
        Given I have "admin" access token
        When I send a GET request to "/api/user"
        Then the response code should be 200
        And the response should contain json:
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
        Given I have "admin" access token
        When I send a GET request to "/api/testItem?limit=5&offset=5"
        Then the response code should be 200
        And the response should contain json:
        """
        [
            {"id":6,"foo":"bar"},
            {"id":7,"foo":"bar"},
            {"id":8,"foo":"bar"},
            {"id":9,"foo":"bar"},
            {"id":10,"foo":"bar"}
        ]
        """
        And the response should not contain "11"

    Scenario: I can create user
        Given I have "admin" access token
        When I send a POST request to "/api/user" with body:
        """
        {
            "name":"name_Bob",
            "email":"email_Bob@api.com",
            "password": "pass_Bob"
        }
        """
        Then the response code should be 201
        And the response should contain json:
        """
        {
            "id": 3,
            "name":"name_Bob",
            "email":"email_Bob@api.com",
            "password": "pass_Bob"
        }
        """

    Scenario: I can delete user
        Given I have "admin" access token
        When I send a DELETE request to "/api/user/3"
        Then the response code should be 200
        And the response should contain json:
        """
        {
            "status":"success"
        }
        """
        And I send a GET request to "/api/user/3"
        And the response code should be 404

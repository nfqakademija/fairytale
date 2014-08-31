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
            "name": "The Admin",
            "email": "admin@admin.com"
        }
        """

    Scenario: I can read user index
        Given I am authenticated as "admin"
        When I send a GET request to "/api/user?limit=2"
        Then print last response
        Then the response code should be 200
        And the response should be json:
        """
        [
            {
                "id": 1,
                "email": "admin@admin.com",
                "name": "The Admin"
            },
            {
                "id": 2,
                "email": "user@user.com",
                "name": "The User"
            }
        ]
        """

    Scenario: I can skip 5 test items and read 5 following
        Given I am authenticated as "admin"
        When I send a GET request to "/api/user?limit=1&offset=1"
        Then print last response
        Then the response code should be 200
        And the response should be json:
        """
        [
            {
                "id":   2,
                "email": "user@user.com",
                "name": "The User"
            }
        ]
        """

    Scenario: I can create user
        Given I am authenticated as "admin"
        When I send a POST request to "/api/user" with body:
        """
        {
            "name":     "John",
            "surname":  "Doe",
            "username": "john.doe",
            "email":    "john.doe@api.com",
            "password": "secret"
        }
        """
        Then the response code should be 201
        And the response should be json:
        """
        {
            "id":       13,
            "name":     "John",
            "surname":  "Doe",
            "username": "john.doe",
            "email":    "john.doe@api.com",
            "password": "secret"
        }
        """

    Scenario: I can update user
        Given I am authenticated as "admin"
        When I send a PUT request to "/api/user/2" with body:
        """
        {
            "name":"John Doe"
        }
        """
        Then the response code should be 200
        And the response should be json:
        """
        {
            "id": 2,
            "name":"John Doe"
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

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
        Then print response
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

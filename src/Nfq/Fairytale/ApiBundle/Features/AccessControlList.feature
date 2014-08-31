@api
Feature: Access Control List
    In order to use API
    As an API user
    An API has to be able to limit operation by role

    Scenario: I can read user data via API as an unauthorized user
        When I send a GET request to "/api/user/1"
        Then the response code should be 403
        And the response should be json:
        """
        {
            "code": 403,
            "message": "Forbidden"
        }
        """

    Scenario: I can read user data via API as an registered user
        Given I am authenticated as "user"
        When I send a GET request to "/api/user/1"
        Then the response code should be 200
        And the response should be json:
        """
        {
            "id": 1,
            "name": "The Admin"
        }
        """

    Scenario: I can read user data via API as an admin
        Given I am authenticated as "admin"
        When I send a GET request to "/api/user/1"
        Then the response code should be 200
        And the response should be json:
        """
        {
            "id": 1,
            "name": "The Admin",
            "email": "admin@admin.com"
        }
        """

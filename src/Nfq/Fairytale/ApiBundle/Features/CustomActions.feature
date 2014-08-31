@api
Feature: Custom actions
    In order to use API
    As an API user
    An API has to support custom actions

    Scenario: I can count resource's instances
        Given I am authenticated as "admin"
        When I send a GET request to "/api/user/count"
        Then print last response
        Then the response code should be 200
        And the response should be json:
        """
        {
            "count": "2"
        }
        """

    Scenario: I can clone resource's instance
        Given I am authenticated as "admin"
        When I send a GET request to "/api/user/1/test"
        Then the response code should be 200
        And the response should be json:
        """
        {
            "id": 1,
            "name": "The Admin",
            "email": "admin@admin.com"
        }
        """

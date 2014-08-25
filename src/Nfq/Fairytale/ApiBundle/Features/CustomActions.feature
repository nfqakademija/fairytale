@api
Feature: Custom actions
    In order to use API
    As an API user
    An API has to support custom actions

    Scenario: I can read resource's metadata
        Given I have "admin" access token
        When I send a GET request to "/api/user/doesNotExist"
        Then the response code should be 400

    Scenario: I can read resource's metadata
        Given I have "admin" access token
        When I send a GET request to "/api/user/meta"
        Then the response code should be 200
        And the response should contain json:
        """
        {
            "resource": "user",
            "count": "2"
        }
        """

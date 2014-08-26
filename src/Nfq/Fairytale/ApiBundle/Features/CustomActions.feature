@api
Feature: Custom actions
    In order to use API
    As an API user
    An API has to support custom actions

    Scenario: I can count resource's instances
        Given I have "admin" access token
        When I send a GET request to "/api/user/count"
        Then the response code should be 200
        And the response should contain json:
        """
        {
            "count": "2"
        }
        """

    Scenario: I can clone resource's instance
        Given I have "admin" access token
        When I send a GET request to "/api/user/1/clone"
        Then the response code should be 201
        And the response should contain json:
        """
        {
            "id": 3,
            "name":"name_Foo",
            "email":"email_bar@api.com",
            "password": "pass_secret"
        }
        """

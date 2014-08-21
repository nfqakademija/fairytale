@api
Feature: API
    In order to use API
    As an API user
    An API has to be usable

    Scenario: I can read user data via API as an unauthorized user
        Given I have "no" access token
        When I go to "/api/user/1"
        Then the response status code should be 403
        And the response should not contain "name_Foo"
        And the response should not contain "email_bar@api.com"
        And the response should not contain "pass_secret"

    Scenario: I can read user data via API as an registered user
        Given I have "user" access token
        When I go to "/api/user/1"
        Then the response status code should be 200
        And the response should contain "name_Foo"
        And the response should contain "email_bar@api.com"
        And the response should not contain "pass_secret"

    Scenario: I can read user data via API as an admin
        Given I have "admin" access token
        When I go to "/api/user/1"
        Then the response status code should be 200
        And the response should contain "name_Foo"
        And the response should contain "email_bar@api.com"
        And the response should contain "pass_secret"

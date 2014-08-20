@api
Feature: API
    In order to use API
    As an API user
    An API has to be usable

    Scenario: I can read user data via API
        Given "file" is data source
        When I go to "/api/user/1"
        Then the response should contain "admin"
#        Then the response should not contain "password"

Feature: Manage Users data via the RESTful API

  In order to offer the User resource via an hypermedia API
  As a client software developer
  I need to be able to retrieve, create, update, and delete JSON encoded User resources


  Background:
    Given there are Users with the following details:
      | uid | username | email          | password |
      | u1  | peter    | peter@test.com | testpass |
      | u2  | john     | john@test.org  | johnpass |
#    And there are Accounts with the following details:
#    | uid | name     | users |
#    | a1  | account1 | u1    |
    And I am successfully logged in with username: "peter", and password: "testpass"
    And when consuming the endpoint I use the "headers/content-type" of "application/json"


  Scenario: User cannot GET a Collection of User objects
    When I send a "GET" request to "/api/users"
    Then the response code should be 405


  Scenario: User can GET their personal data by their unique ID
    When I send a "GET" request to "/api/users/u1"
    Then the response code should be 200
    And the response header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the response should contain json:
      """
      {
        "id": "u1",
        "email": "peter@test.com",
        "username": "peter"
      }
      """


  Scenario: User cannot GET a different User's personal data
    When I send a "GET" request to "/users/u2"
    Then the response code should be 403
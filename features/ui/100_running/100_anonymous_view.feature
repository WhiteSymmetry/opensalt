Feature: An anonymous user can view the home page
  In order to confirm the application is up and running
  As an anonymous user
  I need to see a homepage

  Scenario: An anonymous user lands on the front page
    Given I am on the homepage
    Then I should see "Competency Frameworks"
    And I should see "About OpenSALT"
    And I should see "Sign in" in the "a.login" element

    When I follow "About OpenSALT"
    Then I should see "Open Source"
    And I should see "Sign in" in the "a.login" element

    When I follow "Sign in"
    Then I should see "Username"
    And I should see "Password"
    And I should see "Login"
